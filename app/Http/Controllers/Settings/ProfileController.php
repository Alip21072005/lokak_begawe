<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\ProfileDeleteRequest;
use App\Models\Kategori;
use App\Models\Lokasi;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman pengaturan profil dengan data pendukung secara dinamis.
     */
    public function edit(Request $request): Response
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        // Ambil role string (menghindari error jika menggunakan Enum di database)
        $role = $user->role instanceof \UnitEnum ? $user->role->value : $user->role;

        // Pastikan load relasi yang dibutuhkan
        $user->load(['mitra', 'pelamar']);

        return Inertia::render('settings/Profile', [
            'mustVerifyEmail' => $user instanceof MustVerifyEmail,
            'status' => $request->session()->get('status'),
            'role' => $role,
            'kategoris' => Kategori::orderBy('nama_kategori', 'asc')->get(),
            'lokasis' => Lokasi::orderBy('nama_lokasi', 'asc')->get(),
            'mitra' => $user->mitra,     // Berisi data jika role = mitra
            'pelamar' => $user->pelamar, // Berisi data jika role = pelamar
        ]);
    }

    /**
     * Memperbarui informasi profil User dan data spesifik role (Mitra/Pelamar).
     */
    public function update(Request $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        $role = $user->role instanceof \UnitEnum ? $user->role->value : $user->role;

        // 1. Aturan Validasi Dasar (Akun User)
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'current_password' => ['nullable', 'current_password'],
            'password' => ['nullable', 'confirmed', 'min:8'],
        ];

        // 2. Tambahkan Aturan Validasi Khusus Berdasarkan Role
        if ($role === 'mitra') {
            $rules = array_merge($rules, [
                'nama_mitra' => 'nullable|string|max:255',
                'kategori_id' => 'nullable|exists:kategoris,id',
                'lokasi_id' => 'nullable|exists:lokasis,id',
                'alamat_mitra' => 'nullable|string',
                'website_mitra' => 'nullable|url',
                'deskripsi_mitra' => 'nullable|string',
                'logo_mitra' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            ]);
        } elseif ($role === 'pelamar') {
            $rules = array_merge($rules, [
                'nama_pelamar' => 'nullable|string|max:255',
                'nohp_pelamar' => 'nullable|string|max:20',
                'lokasi_id' => 'nullable|exists:lokasis,id',
                'jenis_kelamin' => 'nullable|in:L,P',
                'alamat_pelamar' => 'nullable|string',
                'foto_pelamar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
                'cv_pelamar' => 'nullable|mimes:pdf|max:5120',
            ]);
        }

        $request->validate($rules);

        // 3. Update Data User (Akun Login)
        $user->fill($request->only('name', 'email'));

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }
        $user->save();

        // 4. Update Data Spesifik Role
        if ($role === 'mitra') {
            $mitraData = $request->only(['nama_mitra', 'kategori_id', 'lokasi_id', 'alamat_mitra', 'website_mitra', 'deskripsi_mitra']);

            if ($request->hasFile('logo_mitra')) {
                if ($user->mitra && $user->mitra->logo_mitra) {
                    Storage::disk('public')->delete($user->mitra->logo_mitra);
                }
                $mitraData['logo_mitra'] = $request->file('logo_mitra')->store('logos', 'public');
            }

            $user->mitra()->updateOrCreate(['user_id' => $user->id], $mitraData);
        } elseif ($role === 'pelamar') {
            $pelamarData = $request->only(['nama_pelamar', 'nohp_pelamar', 'lokasi_id', 'jenis_kelamin', 'alamat_pelamar']);

            // ---> INI KUNCI FIX-NYA <---
            // Menambahkan email_pelamar dari email akun agar database tidak error
            $pelamarData['email_pelamar'] = $request->email;

            if ($request->hasFile('foto_pelamar')) {
                if ($user->pelamar && $user->pelamar->foto_pelamar) {
                    Storage::disk('public')->delete($user->pelamar->foto_pelamar);
                }
                $pelamarData['foto_pelamar'] = $request->file('foto_pelamar')->store('pelamar/foto', 'public');
            }

            if ($request->hasFile('cv_pelamar')) {
                if ($user->pelamar && $user->pelamar->cv_pelamar) {
                    Storage::disk('public')->delete($user->pelamar->cv_pelamar);
                }
                $pelamarData['cv_pelamar'] = $request->file('cv_pelamar')->store('pelamar/cv', 'public');
            }

            $user->pelamar()->updateOrCreate(['user_id' => $user->id], $pelamarData);
        }

        return back()->with('status', 'Profil berhasil diperbarui!');
    }

    /**
     * Menghapus profil pengguna secara permanen beserta file-filenya.
     */
    public function destroy(ProfileDeleteRequest $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        $role = $user->role instanceof \UnitEnum ? $user->role->value : $user->role;

        Auth::logout();

        if ($role === 'mitra' && $user->mitra && $user->mitra->logo_mitra) {
            Storage::disk('public')->delete($user->mitra->logo_mitra);
        } elseif ($role === 'pelamar' && $user->pelamar) {
            if ($user->pelamar->foto_pelamar) {
                Storage::disk('public')->delete($user->pelamar->foto_pelamar);
            }
            if ($user->pelamar->cv_pelamar) {
                Storage::disk('public')->delete($user->pelamar->cv_pelamar);
            }
        }

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}