<?php

namespace App\Http\Controllers;

use App\Models\Lowongan;
use App\Models\Mitra;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LandingController extends Controller
{
    public function index()
    {
        // 1. Ambil lowongan terbaru yang AKTIF & BELUM EXPIRED
        $lowongan = Lowongan::with(['mitra', 'lokasi'])
            ->where('status_lowongan', 'verified') // Hanya yang sudah diverifikasi admin
            ->where('tanggal_expired', '>=', now()->toDateString()) // Belum melewati tanggal hari ini
            ->latest()
            ->take(4)
            ->get()
            ->map(function ($job) {
                return [
                    'id' => $job->id,
                    'judul_lowongan' => $job->judul_lowongan,
                    'gaji_min' => $job->gaji_min,
                    'tipe_pekerjaan' => $job->tipe_pekerjaan,
                    'mitra' => [
                        'nama_mitra' => $job->mitra->nama_mitra,
                        'logo' => $job->mitra->logo_mitra ? asset('storage/' . $job->mitra->logo_mitra) : null,
                    ],
                    'lokasi' => [
                        'nama_lokasi' => $job->lokasi->nama_lokasi ?? 'Bengkulu',
                    ],
                ];
            });

        // 2. Ambil mitra teratas berdasarkan jumlah ulasan (ratings_count)
        // Menggunakan withAvg untuk efisiensi performa database
        $mitra = Mitra::with(['lokasi'])
            ->withCount(['lowongan', 'ratings'])
            ->withAvg('ratings', 'bintang')
            ->orderBy('ratings_count', 'desc') // Urutkan ulasan terbanyak
            ->orderBy('ratings_avg_bintang', 'desc') // Lalu rating tertinggi
            ->take(4)
            ->get()
            ->map(function ($m) {
                return [
                    'id' => $m->id,
                    'nama_mitra' => $m->nama_mitra,
                    'lowongan_count' => $m->lowongan_count,
                    'review_count' => $m->ratings_count,
                    'rating_avg' => round($m->ratings_avg_bintang ?? 0, 1),
                    'logo' => $m->logo_mitra ? asset('storage/' . $m->logo_mitra) : null,
                    'lokasi' => $m->lokasi->nama_lokasi ?? 'Bengkulu',
                ];
            });

        return Inertia::render('Welcome', [
            'lowonganTerbaru' => $lowongan,
            'mitraTeratas' => $mitra,
        ]);
    }
}