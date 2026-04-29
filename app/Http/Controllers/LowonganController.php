<?php

namespace App\Http\Controllers;

use App\Models\Lowongan;
use App\Models\Lokasi;
use App\Models\Kategori;
use App\Models\Pelamar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class LowonganController extends Controller
{
    public function index(Request $request)
    {
        // 1. Query dasar dengan Eager Loading agar tidak berat
        $query = Lowongan::with(['mitra.kategori', 'lokasi'])
            ->where('status_lowongan', 'verified')
            ->where('tanggal_expired', '>=', now()->toDateString());

        // 2. Filter Pencarian Multi-Field (Posisi, Perusahaan, Keahlian)
        if ($request->keyword) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('judul_lowongan', 'like', '%' . $keyword . '%')
                    ->orWhere('deskripsi_lowongan', 'like', '%' . $keyword . '%')
                    ->orWhereHas('mitra', function ($mq) use ($keyword) {
                        $mq->where('nama_mitra', 'like', '%' . $keyword . '%')
                            ->orWhere('deskripsi_mitra', 'like', '%' . $keyword . '%');
                    })
                    ->orWhereHas('skills', function ($sq) use ($keyword) {
                        $sq->where('nama_skill', 'like', '%' . $keyword . '%');
                    });
            });
        }

        if ($request->category) {
            $query->whereHas('mitra', function ($q) use ($request) {
                $q->where('kategori_id', $request->category);
            });
        }

        if ($request->location) {
            $query->where('lokasi_id', $request->location);
        }

        // 3. Wajib pakai paginate agar props .data dan .links terbaca di Vue
        $lowongans = $query->latest()->paginate(12)->withQueryString();

        return Inertia::render('Lowongan', [
            'lowongans' => $lowongans,
            'lokasis' => Lokasi::orderBy('nama_lokasi', 'asc')->get(),
            'kategoris' => Kategori::orderBy('nama_kategori', 'asc')->get(),
            'filters' => $request->only(['keyword', 'category', 'location']),
        ]);
    }

    public function show($id)
    {
        $lowongan = Lowongan::with(['mitra.kategori', 'lokasi', 'skills'])->findOrFail($id);

        $isOwner = Auth::check() && Auth::user()->role === 'mitra' && Auth::user()->mitra->id === $lowongan->mitra_id;
        $isAdmin = Auth::check() && Auth::user()->role === 'admin';

        if (!$isOwner && !$isAdmin) {
            if ($lowongan->status_lowongan !== 'verified' || $lowongan->tanggal_expired < now()->toDateString()) {
                abort(404, 'Lowongan tidak ditemukan.');
            }
        }

        $authPelamar = null;
        if (Auth::check() && Auth::user()->role === 'pelamar') {
            $authPelamar = Pelamar::where('user_id', Auth::id())->first();
        }

        return Inertia::render('Detail/Detaillowongan', [
            'lowongan' => [
                'id'                => $lowongan->id,
                'judul'             => $lowongan->judul_lowongan,
                'deskripsi'         => $lowongan->deskripsi_lowongan,
                'tipe'              => $lowongan->tipe_pekerjaan,
                'gaji_min'          => $lowongan->gaji_min,
                'gaji_max'          => $lowongan->gaji_max,
                'deadline'          => $lowongan->tanggal_expired,
                'status'            => $lowongan->status_lowongan,
                'minimal_pendidikan' => $lowongan->minimal_pendidikan,
                'minimal_pengalaman' => $lowongan->minimal_pengalaman,
                'skills'            => $lowongan->skills,
                'lokasi_nama'       => $lowongan->lokasi->nama_lokasi ?? 'Bengkulu',
                'perusahaan'        => [
                    'id'        => $lowongan->mitra->id,
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
