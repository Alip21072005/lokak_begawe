<?php

namespace App\Http\Controllers\Pelamar;

use App\Http\Controllers\Controller;
use App\Models\Lowongan;
use App\Models\Lokasi;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class LowongankerjaController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 1. Ambil input filter dari request (jika ada)
        $search = $request->input('search');
        $lokasiId = $request->input('lokasi');
        $kategoriId = $request->input('kategori');

        // 2. Query utama dengan Filter Tanggal Expired & Status Verified
        $lowongan = Lowongan::with(['mitra.kategori', 'lokasi'])
            ->where('status_lowongan', 'verified') // Wajib sudah diverifikasi admin
            ->where('tanggal_expired', '>=', now()->toDateString()) // TIDAK TAMPIL jika sudah lewat tanggal
            ->when($search, function ($query, $search) {
                $query->where('judul_lowongan', 'like', "%{$search}%")
                    ->orWhereHas('mitra', function ($q) use ($search) {
                        $q->where('nama_mitra', 'like', "%{$search}%");
                    });
            })
            ->when($lokasiId, function ($query, $lokasiId) {
                $query->where('lokasi_id', $lokasiId);
            })
            ->when($kategoriId, function ($query, $kategoriId) {
                $query->whereHas('mitra', function ($q) use ($kategoriId) {
                    $q->where('kategori_id', $kategoriId);
                });
            })
            ->orderBy('created_at', 'desc')
            ->get()
            // Menambahkan format gaji agar lebih mudah dibaca di Vue
            ->map(fn($job) => [
                ...(array) $job,
                'gaji_format' => 'Rp ' . number_format($job->gaji_min, 0, ',', '.') . ' - ' . number_format($job->gaji_max, 0, ',', '.')
            ]);

        // 3. Tarik data pendukung untuk dropdown filter
        $lokasis = Lokasi::orderBy('nama_lokasi', 'asc')->get();
        $kategoris = Kategori::orderBy('nama_kategori', 'asc')->get();

        return Inertia::render('Pelamar/Lowongankerja', [
            'auth' => [
                'user' => $user->load('pelamar')
            ],
            'lowonganList' => $lowongan,
            'lokasis' => $lokasis,
            'kategoris' => $kategoris,
            'filters' => [
                'search' => $search,
                'lokasi' => $lokasiId,
                'kategori' => $kategoriId,
            ]
        ]);
    }
}