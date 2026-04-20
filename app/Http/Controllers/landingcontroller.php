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
        // Mengambil lowongan terbaru dengan relasi mitra dan lokasi
        $lowongan = Lowongan::with(['mitra', 'lokasi'])
            ->latest()
            ->take(4)
            ->get();

        // Mengambil mitra teratas dan menghitung jumlah lowongan (relasi: lowongan)
        $mitra = Mitra::with(['lokasi'])
            ->withCount('lowongan') // Ini akan menghasilkan 'lowongan_count'
            ->take(4)
            ->get();

        return Inertia::render('Welcome', [
            'lowonganTerbaru' => $lowongan,
            'mitraTeratas' => $mitra,
        ]);
    }
}