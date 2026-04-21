<?php

namespace App\Http\Controllers\Pelamar;

use App\Http\Controllers\Controller;
use App\Models\Lowongan;
use App\Models\Lokasi;   // Wajib tambahkan ini
use App\Models\Kategori; // Wajib tambahkan ini
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class LowongankerjaController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 1. Tarik lowongan lengkap dengan relasi mitra (termasuk kategorinya) dan lokasi
        $lowongan = Lowongan::with(['mitra.kategori', 'lokasi'])
            ->orderBy('created_at', 'desc')
            ->get();

        // 2. Tarik semua data lokasi dan kategori untuk bahan dropdown filter
        $lokasis = Lokasi::orderBy('nama_lokasi', 'asc')->get();
        $kategoris = Kategori::orderBy('nama_kategori', 'asc')->get();

        return Inertia::render('Pelamar/Lowongankerja', [
            'auth' => [
                'user' => $user->load('pelamar')
            ],
            'lowonganList' => $lowongan,
            'lokasis' => $lokasis,       // Kirim ke Vue
            'kategoris' => $kategoris,   // Kirim ke Vue
        ]);
    }
}