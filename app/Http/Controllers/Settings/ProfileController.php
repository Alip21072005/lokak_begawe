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
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman pengaturan profil dengan data pendukung.
     */
    public function edit(Request $request): Response
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        return Inertia::render('settings/Profile', [
            'mustVerifyEmail' => $user instanceof MustVerifyEmail,
            'status' => $request->session()->get('status'),
            // Kirim data pendukung untuk dropdown dan form
            'kategoris' => Kategori::all(),
            'lokasis' => Lokasi::all(),
            'mitra' => $user->load('mitra')->mitra,
        ]);
    }

    /**
     * Memperbarui informasi profil User dan data Mitra.
     */
    public function update(Request $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        // 1. Validasi Gabungan (User & Mitra)
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'current_password' => ['nullable', 'current_password'],
            'password' => ['nullable', 'confirmed', 'min:8'],
            'nama_mitra' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'lokasi_id' => 'required|exists:lokasis,id',
            'alamat_mitra' => 'required|string',
            'website_mitra' => 'nullable|url',
            'deskripsi_mitra' => 'required|string',
            'logo_mitra' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // 2. Update Data User
        $user->fill($request->only('name', 'email'));

        if ($request->filled('password')) {
            $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }
        $user->save();

        // 3. Update Data Mitra
        $mitra = $user->mitra;
        $mitraData = $request->only([
            'nama_mitra',
            'kategori_id',
            'lokasi_id',
            'alamat_mitra',
            'website_mitra',
            'deskripsi_mitra'
        ]);

        // 4. Handle Upload Logo Baru
        if ($request->hasFile('logo_mitra')) {
            // Hapus logo lama jika ada
            if ($mitra->logo_mitra) {
                Storage::disk('public')->delete($mitra->logo_mitra);
            }

            // Simpan logo baru ke folder 'logos' di disk public
            $mitraData['logo_mitra'] = $request->file('logo_mitra')->store('logos', 'public');
        }

        $mitra->update($mitraData);

        // Flash message menggunakan toast/status
        return back()->with('status', 'Profil perusahaan berhasil diperbarui!');
    }

    /**
     * Menghapus profil pengguna secara permanen.
     */
    public function destroy(ProfileDeleteRequest $request): RedirectResponse
    {
        $user = $request->user();

        Auth::logout();

        // Jika mitra punya logo, hapus filenya sebelum akun dihapus
        if ($user->mitra && $user->mitra->logo_mitra) {
            Storage::disk('public')->delete($user->mitra->logo_mitra);
        }

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}