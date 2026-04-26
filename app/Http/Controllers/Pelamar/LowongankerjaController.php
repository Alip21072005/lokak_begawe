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

        // 1. Ambil input filter
        $search = $request->input('search');
        $lokasiId = $request->input('lokasi');
        $kategoriId = $request->input('kategori');

        // 2. Query utama
        // Ganti map() menjadi transform() atau manipulasi koleksi agar tetap objek Eloquent
        $lowongan = Lowongan::with(['mitra.kategori', 'lokasi', 'mitra.lokasi'])
            ->where('status_lowongan', 'verified')
            ->where('tanggal_expired', '>=', now()->toDateString())
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('judul_lowongan', 'like', "%{$search}%")
                        ->orWhereHas('mitra', function ($sq) use ($search) {
                            $sq->where('nama_mitra', 'like', "%{$search}%");
                        });
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
            ->get();

        // 3. Tarik data pendukung
        $lokasis = Lokasi::orderBy('nama_lokasi', 'asc')->get();
        $kategoris = Kategori::orderBy('nama_kategori', 'asc')->get();

        return Inertia::render('Pelamar/Lowongankerja', [
            'auth' => [
                'user' => $user ? $user->load('pelamar') : null
            ],
            'lowonganList' => $lowongan, // Kirim koleksi asli agar relasi mitra & lokasi tidak hilang
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