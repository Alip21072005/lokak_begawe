<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\Lowongan;
use App\Models\Lokasi;
use App\Models\Transaksi;
use App\Models\MasterSkill;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PasanglowonganController extends Controller
{
    /**
     * Menampilkan daftar lowongan milik mitra (History)
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $mitra = $user->mitra;

        $myJobs = Lowongan::with(['lokasi', 'skills'])
            ->where('mitra_id', $mitra->id)
            ->latest()
            ->get();

        return Inertia::render('Mitra/Pasanglowongan', [
            'auth' => [
                'user' => $user->load('mitra'),
            ],
            'lokasis' => Lokasi::orderBy('nama_lokasi', 'asc')->get(),
            'masterSkills' => MasterSkill::orderBy('nama_skill', 'asc')->get(),
            'myJobs' => $myJobs,
        ]);
    }

    /**
     * Tahap 1: Simpan Informasi Lowongan & Kriteria
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul_lowongan' => ['required', 'string', 'max:255'],
            'tipe_pekerjaan' => ['required', 'string', Rule::in(['Full-time', 'Part-time', 'Internship', 'Freelance'])],
            'gaji_min' => ['required', 'numeric', 'min:0'],
            'gaji_max' => ['required', 'numeric', 'min:0', 'gte:gaji_min'],
            'lokasi_id' => ['required', 'exists:lokasis,id'],
            'deskripsi_lowongan' => ['required', 'string'],
            'minimal_pendidikan' => ['nullable', 'string', 'max:100'],
            'minimal_pengalaman' => ['nullable', 'integer', 'min:0', 'max:50'],
            'required_skills' => ['nullable', 'array'],
            'required_skills.*' => ['exists:master_skills,id'],
        ], [
            'gaji_max.gte' => 'Gaji maksimum harus lebih besar atau sama dengan gaji minimum.',
            'minimal_pengalaman.min' => 'Minimal pengalaman tidak boleh minus.',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $mitra = $user->mitra;

        $lowongan = DB::transaction(function () use ($validated, $mitra) {
            $job = Lowongan::create([
                'mitra_id' => $mitra->id,
                'lokasi_id' => $validated['lokasi_id'],
                'judul_lowongan' => $validated['judul_lowongan'],
                'deskripsi_lowongan' => $validated['deskripsi_lowongan'],
                'tipe_pekerjaan' => $validated['tipe_pekerjaan'],
                'gaji_min' => (int) $validated['gaji_min'],
                'gaji_max' => (int) $validated['gaji_max'],
                'minimal_pendidikan' => $validated['minimal_pendidikan'] ?? null,
                'minimal_pengalaman' => max(0, (int) ($validated['minimal_pengalaman'] ?? 0)),
                'status_lowongan' => 'pending',
                'tanggal_expired' => now()->addMonths(1),
            ]);

            if (!empty($validated['required_skills'])) {
                $skillsData = [];
                foreach ($validated['required_skills'] as $skillId) {
                    $skillsData[$skillId] = ['id' => (string) Str::uuid()];
                }
                $job->skills()->sync($skillsData);
            }

            return $job;
        });

        return redirect()->route('mitra.pembayaran.pilih', $lowongan->id);
    }

    /**
     * Update data lowongan & kriteria
     */
    public function update(Request $request, $id)
    {
        $lowongan = Lowongan::findOrFail($id);

        if ($lowongan->mitra_id !== Auth::user()->mitra->id) {
            abort(403);
        }

        $validated = $request->validate([
            'judul_lowongan' => ['required', 'string', 'max:255'],
            'tipe_pekerjaan' => ['required', 'string', Rule::in(['Full-time', 'Part-time', 'Internship', 'Freelance'])],
            'gaji_min' => ['required', 'numeric', 'min:0'],
            'gaji_max' => ['required', 'numeric', 'min:0', 'gte:gaji_min'],
            'lokasi_id' => ['required', 'exists:lokasis,id'],
            'deskripsi_lowongan' => ['required', 'string'],
            'minimal_pendidikan' => ['nullable', 'string', 'max:100'],
            'minimal_pengalaman' => ['nullable', 'integer', 'min:0', 'max:50'],
            'required_skills' => ['nullable', 'array'],
            'required_skills.*' => ['exists:master_skills,id'],
        ], [
            'gaji_max.gte' => 'Gaji maksimum harus lebih besar atau sama dengan gaji minimum.',
            'minimal_pengalaman.min' => 'Minimal pengalaman tidak boleh minus.',
        ]);

        DB::transaction(function () use ($validated, $lowongan) {
            $lowongan->update([
                'judul_lowongan' => $validated['judul_lowongan'],
                'tipe_pekerjaan' => $validated['tipe_pekerjaan'],
                'gaji_min' => (int) $validated['gaji_min'],
                'gaji_max' => (int) $validated['gaji_max'],
                'lokasi_id' => $validated['lokasi_id'],
                'deskripsi_lowongan' => $validated['deskripsi_lowongan'],
                'minimal_pendidikan' => $validated['minimal_pendidikan'] ?? null,
                'minimal_pengalaman' => max(0, (int) ($validated['minimal_pengalaman'] ?? 0)),
                // Opsional: jika ingin setiap edit kembali direview admin, buka komentar di bawah
                // 'status_lowongan' => 'pending',
            ]);

            if (array_key_exists('required_skills', $validated)) {
                $skillsData = [];
                foreach (($validated['required_skills'] ?? []) as $skillId) {
                    $skillsData[$skillId] = ['id' => (string) Str::uuid()];
                }
                $lowongan->skills()->sync($skillsData);
            }
        });

        return redirect()->back()->with('success', 'Lowongan dan kriteria berhasil diperbarui.');
    }

    /**
     * Detail Lowongan untuk dilihat Mitra
     */
    public function show($id)
    {
        $lowongan = Lowongan::with(['lokasi', 'skills', 'mitra'])->findOrFail($id);

        return Inertia::render('Lowongan/Show', [
            'lowongan' => $lowongan,
        ]);
    }

    public function pilihPaket($id)
    {
        $lowongan = Lowongan::findOrFail($id);

        if ($lowongan->mitra_id !== Auth::user()->mitra->id) {
            abort(403);
        }

        return Inertia::render('Mitra/PilihPaket', [
            'lowongan' => $lowongan,
        ]);
    }

    public function bayar(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_paket' => ['required', 'string', 'max:100'],
            'harga' => ['required', 'numeric', 'min:0'],
            'bukti_transfer' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $path = $request->file('bukti_transfer')->store('bukti_pembayaran', 'public');

        Transaksi::create([
            'lowongan_id' => $id,
            'nama_paket' => $validated['nama_paket'],
            'harga' => (int) $validated['harga'],
            'bukti_transfer' => $path,
            'status_pembayaran' => 'pending',
        ]);

        return redirect()->route('mitra.pasanglowongan')
            ->with('success', 'Bukti pembayaran berhasil diunggah.');
    }

    public function destroy($id)
    {
        $lowongan = Lowongan::findOrFail($id);

        if ($lowongan->mitra_id !== Auth::user()->mitra->id) {
            abort(403);
        }

        $lowongan->delete();

        return redirect()->back()->with('success', 'Lowongan berhasil dihapus.');
    }
}