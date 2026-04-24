<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\Lowongan;
use App\Models\Lokasi;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

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

        // Ambil lowongan milik mitra beserta lokasinya
        $myJobs = Lowongan::with('lokasi')
            ->where('mitra_id', $mitra->id)
            ->latest()
            ->get()
            ->map(function ($job) {
                // Tambahkan atribut virtual 'is_expired' untuk mempermudah Vue
                $job->is_expired = $job->tanggal_expired < now()->toDateString();
                return $job;
            });

        return Inertia::render('Mitra/Pasanglowongan', [
            'auth' => [
                'user' => $user->load('mitra')
            ],
            'lokasis' => Lokasi::orderBy('nama_lokasi', 'asc')->get(),
            'myJobs' => $myJobs
        ]);
    }

    /**
     * Tahap 1: Simpan Informasi Lowongan
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
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $mitra = $user->mitra;

        // Simpan lowongan dengan status 'pending' (menunggu pembayaran & verifikasi)
        $lowongan = Lowongan::create([
            'mitra_id' => $mitra->id,
            'lokasi_id' => $request->lokasi_id,
            'judul_lowongan' => $request->judul_lowongan,
            'deskripsi_lowongan' => $request->deskripsi_lowongan,
            'tipe_pekerjaan' => $request->tipe_pekerjaan,
            'gaji_min' => $request->gaji_min,
            'gaji_max' => $request->gaji_max,
            'status_lowongan' => 'pending',
            // Default tayang 1 bulan, akan dihitung ulang saat pembayaran diverifikasi admin
            'tanggal_expired' => now()->addMonths(1),
        ]);

        // Redirect ke halaman pemilihan paket pembayaran
        return redirect()->route('mitra.pembayaran.pilih', $lowongan->id);
    }

    /**
     * Menampilkan halaman pilihan paket iklan
     */
    public function pilihPaket($id)
    {
        $lowongan = Lowongan::findOrFail($id);

        // Pastikan mitra hanya bisa melihat paket untuk lowongannya sendiri
        if ($lowongan->mitra_id !== Auth::user()->mitra->id) {
            abort(403);
        }

        return Inertia::render('Mitra/PilihPaket', [
            'lowongan' => $lowongan
        ]);
    }

    /**
     * Tahap 2: Simpan Bukti Transfer & Paket yang dipilih
     */
    public function bayar(Request $request, $id)
    {
        $request->validate([
            'nama_paket' => 'required|string',
            'harga' => 'required|numeric',
            'bukti_transfer' => 'required|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        // Simpan file bukti transfer ke folder storage/public/bukti_pembayaran
        $path = $request->file('bukti_transfer')->store('bukti_pembayaran', 'public');

        // Buat data transaksi
        Transaksi::create([
            'lowongan_id' => $id,
            'nama_paket' => $request->nama_paket,
            'harga' => $request->harga,
            'bukti_transfer' => $path,
            'status_pembayaran' => 'pending',
        ]);

        return redirect()->route('mitra.pasanglowongan')
            ->with('success', 'Bukti pembayaran berhasil diunggah. Admin akan segera memverifikasi lowongan Anda!');
    }

    /**
     * Update data lowongan (jika masih dalam draf atau butuh revisi)
     */
    public function update(Request $request, $id)
    {
        $lowongan = Lowongan::findOrFail($id);

        if ($lowongan->mitra_id !== Auth::user()->mitra->id) {
            abort(403);
        }

        $lowongan->update($request->all());

        return redirect()->back()->with('success', 'Lowongan berhasil diperbarui.');
    }

    /**
     * Hapus lowongan dari riwayat
     */
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