<?php

namespace App\Http\Controllers;

use App\Models\Pelamar;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class PelamarController extends Controller
{

    public function showPublicProfile($slug)
    {

        $pelamar = Pelamar::with([
            'user',
            'lokasi',
            'skills',
            'pengalamans',
            'pendidikans'
        ])
            ->where('slug', $slug)
            ->firstOrFail();

        return Inertia::render('Detail/Detailpelamar', [
            'pelamar' => $pelamar
        ]);
    }

    public function updatePortfolio(Request $request)
    {

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $pelamar = $user->pelamar;

        $request->validate([
            'bio' => 'nullable|string|max:1000',
            'website_portfolio' => 'nullable|url',
        ]);


        if (!$pelamar->slug) {
            $slug = Str::slug($pelamar->nama_pelamar);


            $count = Pelamar::where('slug', 'LIKE', "{$slug}%")->count();
            $pelamar->slug = $count ? "{$slug}-" . ($count + 1) : $slug;
        }

        $pelamar->update([
            'bio' => $request->bio,
            'website_portfolio' => $request->website_portfolio,
            'slug' => $pelamar->slug
        ]);

        return back()->with('success', 'Portofolio berhasil diperbarui!');
    }
}