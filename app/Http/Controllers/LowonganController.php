<?php

namespace App\Http\Controllers;

use App\Models\Lowongan;
use App\Models\Lokasi;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class LowonganController extends Controller
{
    public function index(Request $request)
    {
        $query = Lowongan::with(['mitra.lokasi', 'lokasi', 'mitra.kategori']);

        if ($request->keyword) {
            $query->where(function ($q) use ($request) {
                $q->where('judul_lowongan', 'like', '%' . $request->keyword . '%')
                    ->orWhereHas('mitra', function ($sq) use ($request) {
                        $sq->where('nama_mitra', 'like', '%' . $request->keyword . '%');
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

        return Inertia::render('Lowongan', [
            'lowongans' => $query->latest()->paginate(12)->withQueryString(),
            'lokasis'   => Lokasi::orderBy('nama_lokasi', 'asc')->get(),
            'kategoris' => Kategori::orderBy('nama_kategori', 'asc')->get(),
            'filters'   => $request->only(['keyword', 'category', 'location']),
        ]);
    }

    public function show($id)
    {
        $lowongan = Lowongan::with(['mitra.kategori', 'lokasi'])->findOrFail($id);

        // Ambil data pelamar jika user sedang login
        $authPelamar = null;
        if (Auth::check() && Auth::user()->role === 'pelamar') {
            $authPelamar = \App\Models\Pelamar::where('user_id', Auth::id())->first();
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
                'lokasi_nama'       => $lowongan->lokasi->nama_lokasi ?? 'Bengkulu',
                'perusahaan'        => [
                    'nama'      => $lowongan->mitra->nama_mitra,
                    'logo'      => $lowongan->mitra->logo_mitra ? asset('storage/' . $lowongan->mitra->logo_mitra) : null,
                    'industri'  => $lowongan->mitra->kategori->nama_kategori ?? 'Umum',
                    'deskripsi' => $lowongan->mitra->deskripsi_mitra,
                    'alamat'    => $lowongan->mitra->alamat_mitra,
                ]
            ],
            'authPelamar' => $authPelamar // Kirim data pelamar ke Vue
        ]);
    }
}