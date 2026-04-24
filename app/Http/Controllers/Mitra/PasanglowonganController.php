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

        // Ambil lowongan milik mitra beserta lokasinya dan skill syaratnya
        $myJobs = Lowongan::with(['lokasi', 'skills'])
            ->where('mitra_id', $mitra->id)
            ->latest()
            ->get()
            ->map(function ($job) {
                $job->is_expired = $job->tanggal_expired < now()->toDateString();
                return $job;
            });

        return Inertia::render('Mitra/Pasanglowongan', [
            'auth' => [
                'user' => $user->load('mitra')
            ],
            'lokasis' => Lokasi::orderBy('nama_lokasi', 'asc')->get(),
            // Kirim master skill agar mitra bisa pilih syarat skill
            'masterSkills' => MasterSkill::orderBy('nama_skill', 'asc')->get(),
            'myJobs' => $myJobs
        ]);
    }

    /**
     * Tahap 1: Simpan Informasi Lowongan & Kriteria
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul_lowongan' => 'required|string|max:255',
            'tipe_pekerjaan' => 'required|string',
            'gaji_min' => 'required|numeric',
            'gaji_max' => 'required|numeric',
            'lokasi_id' => 'required|exists:lokasis,id',
            'deskripsi_lowongan' => 'required|string',
            // Validasi Kriteria Baru
            'minimal_pendidikan' => 'nullable|string',
            'minimal_pengalaman' => 'nullable|integer',
            'required_skills' => 'nullable|array'
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $mitra = $user->mitra;

        // Gunakan Database Transaction agar data lowongan & skill aman (simpan dua-duanya atau tidak sama sekali)
        $lowongan = DB::transaction(function () use ($request, $mitra) {
            $job = Lowongan::create([
                'mitra_id' => $mitra->id,
                'lokasi_id' => $request->lokasi_id,
                'judul_lowongan' => $request->judul_lowongan,
                'deskripsi_lowongan' => $request->deskripsi_lowongan,
                'tipe_pekerjaan' => $request->tipe_pekerjaan,
                'gaji_min' => $request->gaji_min,
                'gaji_max' => $request->gaji_max,
                'minimal_pendidikan' => $request->minimal_pendidikan,
                'minimal_pengalaman' => $request->minimal_pengalaman,
                'status_lowongan' => 'pending',
                'tanggal_expired' => now()->addMonths(1),
            ]);

            // Simpan relasi ke Master Skill (Tabel Pivot lowongan_skills)
            if ($request->has('required_skills')) {
                $skillsData = [];
                foreach ($request->required_skills as $skillId) {
                    // Kita buatkan UUID manual untuk setiap baris di tabel pivot
                    $skillsData[$skillId] = ['id' => (string) \Illuminate\Support\Str::uuid()];
                }

                // Gunakan sync agar jika diedit, skill lama terhapus dan diganti yang baru
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

        $request->validate([
            'judul_lowongan' => 'required|string|max:255',
            'required_skills' => 'nullable|array'
        ]);

        DB::transaction(function () use ($request, $lowongan) {
            $lowongan->update($request->all());

            // Sync skill: menghapus yang lama dan mengganti dengan yang baru dari request
            if ($request->has('required_skills')) {
                $lowongan->skills()->sync($request->required_skills);
            }
        });

        return redirect()->back()->with('success', 'Lowongan dan kriteria berhasil diperbarui.');
    }

    /**
     * Pilih Paket & Bayar (Tetap sama)
     */
    public function pilihPaket($id)
    {
        $lowongan = Lowongan::findOrFail($id);
        if ($lowongan->mitra_id !== Auth::user()->mitra->id) abort(403);

        return Inertia::render('Mitra/PilihPaket', ['lowongan' => $lowongan]);
    }

    public function bayar(Request $request, $id)
    {
        $request->validate([
            'nama_paket' => 'required|string',
            'harga' => 'required|numeric',
            'bukti_transfer' => 'required|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $path = $request->file('bukti_transfer')->store('bukti_pembayaran', 'public');

        Transaksi::create([
            'lowongan_id' => $id,
            'nama_paket' => $request->nama_paket,
            'harga' => $request->harga,
            'bukti_transfer' => $path,
            'status_pembayaran' => 'pending',
        ]);

        return redirect()->route('mitra.pasanglowongan')
            ->with('success', 'Bukti pembayaran berhasil diunggah.');
    }

    public function destroy($id)
    {
        $lowongan = Lowongan::findOrFail($id);
        if ($lowongan->mitra_id !== Auth::user()->mitra->id) abort(403);

        $lowongan->delete();
        return redirect()->back()->with('success', 'Lowongan berhasil dihapus.');
    }
}