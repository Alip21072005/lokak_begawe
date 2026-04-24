<?php

namespace App\Http\Controllers;

use App\Models\Lowongan;
use App\Models\Lokasi;
use App\Models\Kategori;
use App\Models\Pelamar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Carbon\Carbon;

class LowonganController extends Controller
{
    public function index(Request $request)
    {
        // 1. Query dasar dengan Eager Loading yang lebih lengkap
        $query = Lowongan::with(['mitra.lokasi', 'lokasi', 'mitra.kategori'])
            ->where('status_lowongan', 'verified')
            ->where('tanggal_expired', '>=', Carbon::today()->toDateString());

        // 2. Filter Keyword
        if ($request->keyword) {
            $query->where(function ($q) use ($request) {
                $q->where('judul_lowongan', 'like', '%' . $request->keyword . '%')
                    ->orWhereHas('mitra', function ($sq) use ($request) {
                        $sq->where('nama_mitra', 'like', '%' . $request->keyword . '%');
                    });
            });
        }

        // 3. Filter Kategori
        if ($request->category) {
            $query->whereHas('mitra', function ($q) use ($request) {
                $q->where('kategori_id', $request->category);
            });
        }

        // 4. Filter Lokasi
        if ($request->location) {
            $query->where('lokasi_id', $request->location);
        }

        // --- MAP DATA UNTUK MENGHINDARI "PERUSAHAAN RAHASIA" ---
        $lowongans = $query->latest()->paginate(12)->through(fn($item) => [
            'id' => $item->id,
            'judul' => $item->judul_lowongan,
            'tipe' => $item->tipe_pekerjaan,
            'gaji_min' => $item->gaji_min,
            'gaji_max' => $item->gaji_max,
            'tanggal_expired' => $item->tanggal_expired, // Pastikan dikirim untuk diproses Vue
            'lokasi_nama' => $item->lokasi->nama_lokasi ?? 'Bengkulu',
            'perusahaan' => [
                'nama' => $item->mitra->nama_mitra ?? 'Perusahaan Umum',
                'logo' => $item->mitra->logo_mitra ? asset('storage/' . $item->mitra->logo_mitra) : null,
                'kategori' => $item->mitra->kategori->nama_kategori ?? 'Umum',
            ]
        ]);

        return Inertia::render('Lowongan', [
            'lowongans' => $lowongans,
            'lokasis'   => Lokasi::orderBy('nama_lokasi', 'asc')->get(),
            'kategoris' => Kategori::orderBy('nama_kategori', 'asc')->get(),
            'filters'   => $request->only(['keyword', 'category', 'location']),
        ]);
    }

    public function show($id)
    {
        $lowongan = Lowongan::with(['mitra.kategori', 'lokasi'])
            ->where('status_lowongan', 'verified')
            ->where('tanggal_expired', '>=', Carbon::today()->toDateString())
            ->findOrFail($id);

        $authPelamar = null;
        if (Auth::check() && Auth::user()->role === 'pelamar') {
            // Eager load portofolio untuk mempermudah saat mau klik lamar
            $authPelamar = Pelamar::with(['skills', 'pendidikans', 'pengalamans'])
                ->where('user_id', Auth::id())
                ->first();
        }

        return Inertia::render('Detail/Detaillowongan', [
            'lowongan' => [
                'id'            => $lowongan->id,
                'judul'         => $lowongan->judul_lowongan,
                'deskripsi'     => $lowongan->deskripsi_lowongan,
                'tipe'          => $lowongan->tipe_pekerjaan,
                'gaji_min'      => $lowongan->gaji_min,
                'gaji_max'      => $lowongan->gaji_max,
                'deadline'      => $lowongan->tanggal_expired,
                'lokasi_nama'   => $lowongan->lokasi->nama_lokasi ?? 'Bengkulu',
                'perusahaan'    => [
                    'nama'      => $lowongan->mitra->nama_mitra,
                    'logo'      => $lowongan->mitra->logo_mitra ? asset('storage/' . $lowongan->mitra->logo_mitra) : null,
                    'industri'  => $lowongan->mitra->kategori->nama_kategori ?? 'Umum',
                    'deskripsi' => $lowongan->mitra->deskripsi_mitra,
                    'alamat'    => $lowongan->mitra->alamat_mitra,
                ]
            ],
            'authPelamar' => $authPelamar
        ]);
    }
}