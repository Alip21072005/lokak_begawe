<?php

namespace App\Http\Controllers;

use App\Models\Mitra;
use App\Models\Lokasi;
use App\Models\Kategori;
use App\Models\Rating; // WAJIB IMPORT INI
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class MitraController extends Controller
{
    public function index(Request $request)
    {
        // Menggunakan withCount untuk menghitung ulasan dan withAvg untuk rata-rata rating
        $query = Mitra::with(['lokasi', 'kategori', 'lowongan', 'ratings'])
            ->withCount('ratings')
            ->withAvg('ratings', 'bintang');

        // Filter Nama
        if ($request->search) {
            $query->where('nama_mitra', 'like', '%' . $request->search . '%');
        }

        // Filter Lokasi (ID)
        if ($request->lokasi) {
            $query->where('lokasi_id', $request->lokasi);
        }

        // Filter Kategori (ID)
        if ($request->kategori) {
            $query->where('kategori_id', $request->kategori);
        }

        // LOGIKA PENGURUTAN: 
        // 1. Berdasarkan jumlah ulasan terbanyak (ratings_count)
        // 2. Berdasarkan rata-rata bintang tertinggi (ratings_avg_bintang)
        $mitras = $query->orderBy('ratings_count', 'desc')
            ->orderBy('ratings_avg_bintang', 'desc')
            ->get()
            ->map(function ($mitra) {
                return [
                    'id' => (string) $mitra->id,
                    'name' => $mitra->nama_mitra,
                    'location' => $mitra->lokasi->nama_lokasi ?? 'Provinsi Bengkulu',
                    'industry' => $mitra->kategori->nama_kategori ?? 'Umum',
                    'jobs_count' => $mitra->lowongan->count() . ' Lowongan',
                    // Rating dinamis hasil rata-rata database
                    'rating' => (string) ($mitra->ratings_avg_bintang ? round($mitra->ratings_avg_bintang, 1) : 0),
                    'review_count' => $mitra->ratings_count,
                    'logo' => $mitra->logo_mitra ? asset('storage/' . $mitra->logo_mitra) : null,
                ];
            });

        return Inertia::render('Mitra', [
            'mitras' => $mitras,
            'lokasis' => Lokasi::orderBy('nama_lokasi', 'asc')->get(),
            'kategoris' => Kategori::orderBy('nama_kategori', 'asc')->get(),
            'filters' => $request->only(['search', 'lokasi', 'kategori']),
        ]);
    }

    public function show($id)
    {
        // Ambil mitra beserta relasi ulasan dan user yang memberikannya
        $mitra = Mitra::with(['lokasi', 'kategori', 'lowongan', 'ratings.user'])->findOrFail($id);

        return Inertia::render('Detail/Detailmitra', [
            'partnerDetail' => [
                'id' => $mitra->id,
                'name' => $mitra->nama_mitra,
                'industry' => $mitra->kategori->nama_kategori ?? 'Sektor Umum',
                'location' => $mitra->lokasi->nama_lokasi ?? 'Bengkulu, Indonesia',
                'website' => $mitra->website_mitra ?? 'Tidak tersedia',
                'founded' => $mitra->tahun_berdiri ?? '-',
                'size' => $mitra->skala_perusahaan ?? '1-50 Karyawan',
                // Rating rata-rata dinamis
                'rating' => (string) ($mitra->ratings->avg('bintang') ? round($mitra->ratings->avg('bintang'), 1) : 0),
                'reviewCount' => $mitra->ratings->count(),
                // List ulasan dari yang terbaru
                'reviews' => $mitra->ratings->sortByDesc('created_at')->map(fn($rev) => [
                    'user_name' => $rev->user->name ?? 'Anonim',
                    'bintang' => $rev->bintang,
                    'ulasan' => $rev->ulasan,
                    'date' => $rev->created_at->diffForHumans(),
                ])->values(),
                'jobCount' => $mitra->lowongan->count() . ' LOWONGAN AKTIF',
                'description' => $mitra->deskripsi_mitra ?? 'Perusahaan ini belum menambahkan deskripsi profil.',
                'logo' => $mitra->logo_mitra ? asset('storage/' . $mitra->logo_mitra) : null,
                'email' => $mitra->email_mitra,
                'address' => $mitra->alamat_mitra,
                'activeJobs' => $mitra->lowongan->map(fn($job) => [
                    'id' => $job->id,
                    'title' => $job->judul_lowongan,
                    'type' => $job->tipe_pekerjaan ?? 'Full Time',
                ]),
            ]
        ]);
    }

    /**
     * UNTUK MENYIMPAN RATING DARI PELAMAR
     */
    public function storeRating(Request $request, $id)
    {
        // 1. Validasi Input
        $request->validate([
            'bintang' => 'required|integer|min:1|max:5',
            'ulasan' => 'required|string|min:5|max:500',
        ], [
            'bintang.required' => 'Pilih jumlah bintang dulu ya kawan.',
            'ulasan.required' => 'Jangan lupa isi ulasannya sedikit.',
            'ulasan.min' => 'Ulasannya terlalu singkat, minimal 5 karakter.',
        ]);

        // 2. Simpan atau Update (Satu user hanya boleh kasih 1 ulasan per perusahaan)
        Rating::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'mitra_id' => $id,
            ],
            [
                'bintang' => $request->bintang,
                'ulasan' => $request->ulasan,
            ]
        );

        // 3. Kembali dengan Flash Message
        return back()->with('success', 'Ulasan Anda berhasil dikirim!');
    }
}