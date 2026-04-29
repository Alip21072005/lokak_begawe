<?php

namespace App\Http\Controllers;

use App\Models\Lowongan;
use App\Models\Mitra;
use App\Models\Lokasi;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LandingController extends Controller
{
    public function index()
    {
        // 1. Ambil lowongan terbaru yang AKTIF & BELUM EXPIRED
        $lowongan = Lowongan::with(['mitra.kategori', 'lokasi'])
            ->where('status_lowongan', 'verified')
            ->where('tanggal_expired', '>=', now()->toDateString())
            ->latest()
            ->take(8)
            ->get()
            ->map(function ($job) {
                return [
                    'id' => $job->id,
                    'judul_lowongan' => $job->judul_lowongan,
                    'gaji_min' => $job->gaji_min,
                    'tipe_pekerjaan' => $job->tipe_pekerjaan,
                    'created_at' => $job->created_at,
                    'mitra' => [
                        'nama_mitra' => $job->mitra->nama_mitra,
                        'logo' => $job->mitra->logo_mitra ? asset('storage/' . $job->mitra->logo_mitra) : null,
                        'kategori' => [
                            'nama_kategori' => $job->mitra->kategori?->nama_kategori ?? 'Umum',
                        ],
                    ],
                    'lokasi' => [
                        'nama_lokasi' => $job->lokasi->nama_lokasi ?? 'Bengkulu',
                    ],
                ];
            });

        // 2. Ambil mitra teratas
        $mitra = Mitra::with(['lokasi'])
            ->withCount(['lowongan', 'ratings'])
            ->withAvg('ratings', 'bintang')
            ->where('status_mitra', 'verified')
            ->orderBy('ratings_count', 'desc')
            ->orderBy('ratings_avg_bintang', 'desc')
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

        // 3. Data untuk filter dropdown
        $lokasis = Lokasi::orderBy('nama_lokasi', 'asc')->get(['id', 'nama_lokasi']);
        $kategoris = Kategori::orderBy('nama_kategori', 'asc')->get(['id', 'nama_kategori']);

        return Inertia::render('Welcome', [
            'lowonganTerbaru' => $lowongan,
            'mitraTeratas' => $mitra,
            'lokasis' => $lokasis,
            'kategoris' => $kategoris,
        ]);
    }
}
