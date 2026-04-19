<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\Lowongan; // TAMBAHKAN INI
use App\Models\Lokasi;   // TAMBAHKAN INI
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class PasanglowonganController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $mitra = $user->mitra;
        return Inertia::render('Mitra/Pasanglowongan', [
            'auth' => [
                'user' => $user->load('mitra')
            ],
            // Kirim data lokasi ke Vue untuk dropdown
            'lokasis' => Lokasi::orderBy('nama_lokasi', 'asc')->get(),
            'myJobs' => Lowongan::with('lokasi')
                ->where('mitra_id', $mitra->id)
                ->latest()
                ->get()
        ]);
    }

    public function store(Request $request)
    {
        // 1. Validasi
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

        // 2. Simpan ke Database (Gunakan nama kolom yang sesuai migrasi baru)
        Lowongan::create([
            'mitra_id' => $mitra->id,
            'lokasi_id' => $request->lokasi_id, // GUNAKAN INI, BUKAN lokasi_lowongan
            'judul_lowongan' => $request->judul_lowongan,
            'deskripsi_lowongan' => $request->deskripsi_lowongan,
            'tipe_pekerjaan' => $request->tipe_pekerjaan,
            'gaji_min' => $request->gaji_min, // SIMPAN TERPISAH
            'gaji_max' => $request->gaji_max, // SIMPAN TERPISAH
            'status_lowongan' => 'pending',
            'tanggal_expired' => now()->addMonths(1),
        ]);

        return redirect()->route('mitra.dashboard')->with('success', 'Lowongan berhasil diajukan!');
    }

    public function update(Request $request, $id)
    {
        $lowongan = Lowongan::findOrFail($id);
        $lowongan->update($request->all());
        return redirect()->back()->with('success', 'Lowongan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Lowongan::destroy($id);
        return redirect()->back()->with('success', 'Lowongan berhasil dihapus.');
    }
}