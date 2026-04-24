<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\ProfileDeleteRequest;
use App\Models\Kategori;
use App\Models\Lokasi;
use App\Models\MasterInstansi;
use App\Models\MasterPerusahaan;
use App\Models\MasterSkill;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman pengaturan profil dengan Master Data Portofolio.
     */
    public function edit(Request $request): Response
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        $role = $user->role instanceof \UnitEnum ? $user->role->value : $user->role;

        // Load relasi lengkap untuk portofolio pelamar
        $user->load([
            'mitra',
            'pelamar.skills.masterSkill',
            'pelamar.pendidikans.masterInstansi',
            'pelamar.pengalamans.masterPerusahaan'
        ]);

        return Inertia::render('settings/Profile', [
            'mustVerifyEmail' => $user instanceof MustVerifyEmail,
            'status' => $request->session()->get('status'),
            'role' => $role,
            'kategoris' => Kategori::orderBy('nama_kategori', 'asc')->get(),
            'lokasis' => Lokasi::orderBy('nama_lokasi', 'asc')->get(),

            // --- KIRIM MASTER DATA KE FRONTEND ---
            'masterSkills' => MasterSkill::orderBy('nama_skill', 'asc')->get(),
            'masterInstansis' => MasterInstansi::orderBy('nama_instansi', 'asc')->get(),
            'masterPerusahaans' => MasterPerusahaan::orderBy('nama_perusahaan', 'asc')->get(),

            'mitra' => $user->mitra,
            'pelamar' => $user->pelamar,
        ]);
    }

    /**
     * Memperbarui informasi profil dan data portofolio secara menyeluruh.
     */
    public function update(Request $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        $role = $user->role instanceof \UnitEnum ? $user->role->value : $user->role;

        // 1. Aturan Validasi
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'current_password' => ['nullable', 'current_password'],
            'password' => ['nullable', 'confirmed', 'min:8'],
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];

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
                'bio' => 'nullable|string|max:1000',
                'website_portfolio' => 'nullable|url',
                'alamat_pelamar' => 'nullable|string',
                'foto_pelamar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
                'cv_pelamar' => 'nullable|mimes:pdf|max:5120',
                // Validasi Array Portofolio
                'skills' => 'nullable|array',
                'pendidikans' => 'nullable|array',
                'pengalamans' => 'nullable|array',
            ]);
        }

        $request->validate($rules);

        // 2. Update Akun User (Dasar)
        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar) Storage::disk('public')->delete($user->avatar);
            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        $user->save();

        // 3. Update Data Spesifik Role
        if ($role === 'mitra') {
            $mitraData = $request->only(['nama_mitra', 'kategori_id', 'lokasi_id', 'alamat_mitra', 'website_mitra', 'deskripsi_mitra']);
            $mitraData['email_mitra'] = $request->email;

            if ($request->hasFile('logo_mitra')) {
                if ($user->mitra && $user->mitra->logo_mitra) {
                    Storage::disk('public')->delete($user->mitra->logo_mitra);
                }
                $mitraData['logo_mitra'] = $request->file('logo_mitra')->store('mitra/logos', 'public');
            }
            $user->mitra()->updateOrCreate(['user_id' => $user->id], $mitraData);
        } elseif ($role === 'pelamar') {
            $pelamar = $user->pelamar()->updateOrCreate(
                ['user_id' => $user->id],
                $request->only(['nama_pelamar', 'nohp_pelamar', 'lokasi_id', 'jenis_kelamin', 'alamat_pelamar', 'bio', 'website_portfolio'])
            );

            // Update Email Pelamar
            $pelamar->update(['email_pelamar' => $request->email]);

            // Handle Files
            if ($request->hasFile('foto_pelamar')) {
                if ($pelamar->foto_pelamar) Storage::disk('public')->delete($pelamar->foto_pelamar);
                $pelamar->update(['foto_pelamar' => $request->file('foto_pelamar')->store('pelamar/foto', 'public')]);
            }
            if ($request->hasFile('cv_pelamar')) {
                if ($pelamar->cv_pelamar) Storage::disk('public')->delete($pelamar->cv_pelamar);
                $pelamar->update(['cv_pelamar' => $request->file('cv_pelamar')->store('pelamar/cv', 'public')]);
            }

            // --- LOGIKA SIMPAN PORTOFOLIO (SYNC) ---

            // A. Simpan Skills
            $pelamar->skills()->delete();
            if ($request->skills) {
                foreach ($request->skills as $s) {
                    if (!empty($s['master_skill_id'])) {
                        $pelamar->skills()->create(['master_skill_id' => $s['master_skill_id']]);
                    }
                }
            }

            // B. Simpan Pendidikan
            $pelamar->pendidikans()->delete();
            if ($request->pendidikans) {
                foreach ($request->pendidikans as $p) {
                    if (!empty($p['master_instansi_id'])) {
                        $pelamar->pendidikans()->create([
                            'master_instansi_id' => $p['master_instansi_id'],
                            'gelar' => $p['gelar'],
                            'tgl_mulai' => $p['tgl_mulai'],
                            'tgl_lulus' => $p['tgl_lulus'],
                        ]);
                    }
                }
            }

            // C. Simpan Pengalaman
            $pelamar->pengalamans()->delete();
            if ($request->pengalamans) {
                foreach ($request->pengalamans as $ex) {
                    if (!empty($ex['master_perusahaan_id'])) {
                        $pelamar->pengalamans()->create([
                            'master_perusahaan_id' => $ex['master_perusahaan_id'],
                            'posisi' => $ex['posisi'],
                            'tgl_mulai' => $ex['tgl_mulai'],
                            'tgl_selesai' => $ex['is_current'] ? null : $ex['tgl_selesai'],
                            'is_current' => $ex['is_current'] ?? false,
                            'deskripsi' => $ex['deskripsi'],
                        ]);
                    }
                }
            }
        }

        return back()->with('status', 'Profil dan portofolio berhasil diperbarui!');
    }

    /**
     * Menghapus profil dan seluruh aset file.
     */
    public function destroy(ProfileDeleteRequest $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        $role = $user->role instanceof \UnitEnum ? $user->role->value : $user->role;

        Auth::logout();

        if ($user->avatar) Storage::disk('public')->delete($user->avatar);

        if ($role === 'mitra' && $user->mitra?->logo_mitra) {
            Storage::disk('public')->delete($user->mitra->logo_mitra);
        } elseif ($role === 'pelamar' && $user->pelamar) {
            if ($user->pelamar->foto_pelamar) Storage::disk('public')->delete($user->pelamar->foto_pelamar);
            if ($user->pelamar->cv_pelamar) Storage::disk('public')->delete($user->pelamar->cv_pelamar);
        }

        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}