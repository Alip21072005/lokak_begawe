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
    { /* ... tetap sama ... */
    }

    public function show($id)
    {
        // 1. Ambil data dasar
        $lowongan = Lowongan::with(['mitra.kategori', 'lokasi', 'skills'])->findOrFail($id);

        // 2. Cek Akses (Owner/Admin bisa lihat walau pending)
        $isOwner = Auth::check() && Auth::user()->role === 'mitra' && Auth::user()->mitra->id === $lowongan->mitra_id;
        $isAdmin = Auth::check() && Auth::user()->role === 'admin';

        if (!$isOwner && !$isAdmin) {
            if ($lowongan->status_lowongan !== 'verified' || $lowongan->tanggal_expired < now()->toDateString()) {
                abort(404, 'Lowongan tidak ditemukan.');
            }
        }

        // 3. Data Auth Pelamar
        $authPelamar = null;
        if (Auth::check() && Auth::user()->role === 'pelamar') {
            $authPelamar = Pelamar::where('user_id', Auth::id())->first();
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
                'status'        => $lowongan->status_lowongan,
                'minimal_pendidikan' => $lowongan->minimal_pendidikan,
                'minimal_pengalaman' => $lowongan->minimal_pengalaman,
                'skills'        => $lowongan->skills,
                'lokasi_nama'   => $lowongan->lokasi->nama_lokasi ?? 'Bengkulu',
                'perusahaan'    => [
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