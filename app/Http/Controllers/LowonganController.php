<?php

namespace App\Http\Controllers;

use App\Models\Lowongan;
use App\Models\Lokasi;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LowonganController extends Controller
{
    public function index(Request $request)
    {
        $query = Lowongan::with(['mitra.lokasi', 'lokasi', 'mitra.kategori']);

        if ($request->keyword) {
            $query->where('judul_lowongan', 'like', '%' . $request->keyword . '%')
                ->orWhereHas('mitra', function ($q) use ($request) {
                    $q->where('nama_mitra', 'like', '%' . $request->keyword . '%');
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
        $lowongan = Lowongan::with(['mitra', 'lokasi'])->findOrFail($id);
        return Inertia::render('Detail/Detaillowongan', [
            'lowongan' => $lowongan
        ]);
    }
}