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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    public function edit(Request $request): Response
    {
        $user = $request->user();
        $role = $this->normalizeRole($user->role);

        $user->load([
            'mitra',
            'pelamar.lokasi',
            'pelamar.skills.masterSkill',
            'pelamar.pendidikans.masterInstansi',
            'pelamar.pengalamans.masterPerusahaan',
        ]);

        $mitra = $user->mitra;
        $pelamar = $user->pelamar;

        if ($mitra) {
            $mitra->logo_url = $mitra->logo_mitra ? Storage::url($mitra->logo_mitra) : null;
            $mitra->banner_url = $mitra->banner_mitra ? Storage::url($mitra->banner_mitra) : null;
            $mitra->dokumen_url = $mitra->dokumen_mitra ? Storage::url($mitra->dokumen_mitra) : null;
        }

        if ($pelamar) {
            $pelamar->foto_url = $pelamar->foto_pelamar ? Storage::url($pelamar->foto_pelamar) : null;
            $pelamar->cv_url = $pelamar->cv_pelamar ? Storage::url($pelamar->cv_pelamar) : null;
        }

        return Inertia::render('settings/Profile', [
            'mustVerifyEmail' => $user instanceof MustVerifyEmail,
            'status' => $request->session()->get('status'),
            'role' => $role,
            'kategoris' => Kategori::orderBy('nama_kategori')->get(),
            'lokasis' => Lokasi::orderBy('nama_lokasi')->get(),
            'masterSkills' => MasterSkill::orderBy('nama_skill')->get(),
            'masterInstansis' => MasterInstansi::orderBy('nama_instansi')->get(),
            'masterPerusahaans' => MasterPerusahaan::orderBy('nama_perusahaan')->get(),
            'mitra' => $mitra,
            'pelamar' => $pelamar,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();
        $role = $this->normalizeRole($user->role);

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'current_password' => ['nullable', 'required_with:password', 'current_password'],
            'password' => ['nullable', 'confirmed', 'min:8', 'max:255'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];

        if ($role === 'mitra') {
            $rules = array_merge($rules, [
                'nama_mitra' => ['required', 'string', 'max:255'],
                'kategori_id' => ['required', 'exists:kategoris,id'],
                'lokasi_id' => ['required', 'exists:lokasis,id'],
                'alamat_mitra' => ['nullable', 'string'],
                'email_mitra' => ['nullable', 'email', 'max:255'],
                'nohp_mitra' => ['nullable', 'string', 'max:20'],
                'website_mitra' => ['nullable', 'url', 'max:255'],
                'tahun_berdiri' => ['nullable', 'integer', 'min:1900', 'max:' . now()->year],
                'skala_perusahaan' => ['nullable', 'string', 'max:100'],
                'deskripsi_mitra' => ['nullable', 'string', 'max:3000'],
                'logo_mitra' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
                'banner_mitra' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
                'dokumen_mitra' => ['nullable', 'mimes:pdf', 'max:5120'],
            ]);
        }

        if ($role === 'pelamar') {
            $rules = array_merge($rules, [
                'nama_pelamar' => ['required', 'string', 'max:255'],
                'nohp_pelamar' => ['nullable', 'string', 'max:20'],
                'lokasi_id' => ['nullable', 'exists:lokasis,id'],
                'jenis_kelamin' => ['nullable', Rule::in(['L', 'P'])],
                'bio' => ['nullable', 'string', 'max:2000'],
                'website_portfolio' => ['nullable', 'url', 'max:255'],
                'alamat_pelamar' => ['nullable', 'string'],
                'foto_pelamar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
                'cv_pelamar' => ['nullable', 'mimes:pdf', 'max:5120'],

                'skills' => ['nullable', 'array'],
                'skills.*.master_skill_id' => ['required', 'exists:master_skills,id'],

                'pendidikans' => ['nullable', 'array'],
                'pendidikans.*.master_instansi_id' => ['required', 'exists:master_instansis,id'],
                'pendidikans.*.gelar' => ['required', 'string', 'max:255'],
                'pendidikans.*.tgl_mulai' => ['required', 'date'],
                'pendidikans.*.tgl_lulus' => ['nullable', 'date', 'after_or_equal:pendidikans.*.tgl_mulai'],

                'pengalamans' => ['nullable', 'array'],
                'pengalamans.*.master_perusahaan_id' => ['required', 'exists:master_perusahaans,id'],
                'pengalamans.*.posisi' => ['required', 'string', 'max:255'],
                'pengalamans.*.tgl_mulai' => ['required', 'date'],
                'pengalamans.*.tgl_selesai' => ['nullable', 'date'],
                'pengalamans.*.is_current' => ['nullable', 'boolean'],
                'pengalamans.*.deskripsi' => ['nullable', 'string', 'max:3000'],
            ]);
        }

        $validated = $request->validate($rules);

        DB::transaction(function () use ($request, $user, $role, $validated): void {
            $user->name = $validated['name'];
            $user->email = $validated['email'];

            if (!empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }

            if ($request->hasFile('avatar')) {
                if ($user->avatar) {
                    Storage::disk('public')->delete($user->avatar);
                }
                $user->avatar = $request->file('avatar')->store('avatars', 'public');
            }

            $user->save();

            if ($role === 'mitra') {
                $mitraData = [
                    'nama_mitra' => $validated['nama_mitra'],
                    'kategori_id' => $validated['kategori_id'],
                    'lokasi_id' => $validated['lokasi_id'],
                    'alamat_mitra' => $validated['alamat_mitra'] ?? null,
                    'email_mitra' => $validated['email_mitra'] ?? $validated['email'],
                    'nohp_mitra' => $validated['nohp_mitra'] ?? null,
                    'website_mitra' => $validated['website_mitra'] ?? null,
                    'tahun_berdiri' => $validated['tahun_berdiri'] ?? null,
                    'skala_perusahaan' => $validated['skala_perusahaan'] ?? null,
                    'deskripsi_mitra' => $validated['deskripsi_mitra'] ?? null,
                ];

                $mitra = $user->mitra()->updateOrCreate(
                    ['user_id' => $user->id],
                    $mitraData
                );

                if ($request->hasFile('logo_mitra')) {
                    if ($mitra->logo_mitra) Storage::disk('public')->delete($mitra->logo_mitra);
                    $mitra->logo_mitra = $request->file('logo_mitra')->store('mitra/logos', 'public');
                }

                if ($request->hasFile('banner_mitra')) {
                    if ($mitra->banner_mitra) Storage::disk('public')->delete($mitra->banner_mitra);
                    $mitra->banner_mitra = $request->file('banner_mitra')->store('mitra/banners', 'public');
                }

                if ($request->hasFile('dokumen_mitra')) {
                    if ($mitra->dokumen_mitra) Storage::disk('public')->delete($mitra->dokumen_mitra);
                    $mitra->dokumen_mitra = $request->file('dokumen_mitra')->store('mitra/dokumen', 'public');
                }

                $mitra->save();
            }

            if ($role === 'pelamar') {
                $pelamar = $user->pelamar()->updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'nama_pelamar' => $validated['nama_pelamar'],
                        'email_pelamar' => $validated['email'],
                        'nohp_pelamar' => $validated['nohp_pelamar'] ?? null,
                        'lokasi_id' => $validated['lokasi_id'] ?? null,
                        'jenis_kelamin' => $validated['jenis_kelamin'] ?? null,
                        'alamat_pelamar' => $validated['alamat_pelamar'] ?? null,
                        'bio' => $validated['bio'] ?? null,
                        'website_portfolio' => $validated['website_portfolio'] ?? null,
                    ]
                );

                if ($request->hasFile('foto_pelamar')) {
                    if ($pelamar->foto_pelamar) Storage::disk('public')->delete($pelamar->foto_pelamar);
                    $pelamar->foto_pelamar = $request->file('foto_pelamar')->store('pelamar/foto', 'public');
                }

                if ($request->hasFile('cv_pelamar')) {
                    if ($pelamar->cv_pelamar) Storage::disk('public')->delete($pelamar->cv_pelamar);
                    $pelamar->cv_pelamar = $request->file('cv_pelamar')->store('pelamar/cv', 'public');
                }

                $pelamar->save();

                $pelamar->skills()->delete();
                foreach (($validated['skills'] ?? []) as $skill) {
                    $pelamar->skills()->create([
                        'master_skill_id' => $skill['master_skill_id'],
                    ]);
                }

                $pelamar->pendidikans()->delete();
                foreach (($validated['pendidikans'] ?? []) as $edu) {
                    $pelamar->pendidikans()->create([
                        'master_instansi_id' => $edu['master_instansi_id'],
                        'gelar' => $edu['gelar'],
                        'tgl_mulai' => $edu['tgl_mulai'],
                        'tgl_lulus' => $edu['tgl_lulus'] ?? null,
                    ]);
                }

                $pelamar->pengalamans()->delete();
                foreach (($validated['pengalamans'] ?? []) as $exp) {
                    $isCurrent = (bool) ($exp['is_current'] ?? false);

                    $pelamar->pengalamans()->create([
                        'master_perusahaan_id' => $exp['master_perusahaan_id'],
                        'posisi' => $exp['posisi'],
                        'tgl_mulai' => $exp['tgl_mulai'],
                        'tgl_selesai' => $isCurrent ? null : ($exp['tgl_selesai'] ?? null),
                        'is_current' => $isCurrent,
                        'deskripsi' => $exp['deskripsi'] ?? null,
                    ]);
                }
            }
        });

        return back()->with('status', 'Profil berhasil diperbarui.');
    }

    public function destroy(ProfileDeleteRequest $request): RedirectResponse
    {
        $user = $request->user();
        $role = $this->normalizeRole($user->role);

        Auth::logout();

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        if ($role === 'mitra' && $user->mitra) {
            if ($user->mitra->logo_mitra) Storage::disk('public')->delete($user->mitra->logo_mitra);
            if ($user->mitra->banner_mitra) Storage::disk('public')->delete($user->mitra->banner_mitra);
            if ($user->mitra->dokumen_mitra) Storage::disk('public')->delete($user->mitra->dokumen_mitra);
        }

        if ($role === 'pelamar' && $user->pelamar) {
            if ($user->pelamar->foto_pelamar) Storage::disk('public')->delete($user->pelamar->foto_pelamar);
            if ($user->pelamar->cv_pelamar) Storage::disk('public')->delete($user->pelamar->cv_pelamar);
        }

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    private function normalizeRole(mixed $role): string
    {
        return $role instanceof \UnitEnum ? $role->value : (string) $role;
    }
}