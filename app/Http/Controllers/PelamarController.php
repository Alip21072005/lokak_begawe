<?php

namespace App\Http\Controllers;

use App\Models\Pelamar;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PelamarController extends Controller
{
    public function show(string $id)
    {
        $pelamar = Pelamar::with([
            'user',
            'lokasi',
            'skills.masterSkill',
            'pengalamans.masterPerusahaan',
            'pendidikans.masterInstansi',
        ])
            ->where('pelamar_id', $id)
            ->orWhere('user_id', $id)
            ->first();

        if (!$pelamar) {
            return back()->with('error', 'Profil pelamar tidak ditemukan.');
        }

        return Inertia::render('Detail/Detailpelamar', [
            'pelamar' => $pelamar,
        ]);
    }

    public function updatePortfolio(Request $request)
    {
        // method lain biarkan sesuai implementasi kamu saat ini
    }
}