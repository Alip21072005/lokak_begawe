<?php

namespace App\Http\Controllers;

use App\Models\Lowongan;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LowonganController extends Controller
{
    public function index(Request $request)
    {
        // Ambil data dengan relasi mitra dan lokasi
        $query = Lowongan::with(['mitra', 'lokasi']);

        // Filter pencarian berdasarkan judul_lowongan (dari model kamu)
        if ($request->keyword) {
            $query->where('judul_lowongan', 'like', '%' . $request->keyword . '%');
        }

        // Ambil data dengan pagination (biar enteng)
        $lowongans = $query->latest()->paginate(12)->withQueryString();

        return Inertia::render('Lowongan', [
            'lowongans' => $lowongans,
        ]);
    }
}