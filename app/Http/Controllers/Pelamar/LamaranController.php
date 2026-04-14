<?php

namespace App\Http\Controllers\Pelamar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class LamaranController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Di sini nanti kamu bisa ambil data dari DB, contoh:
        // $daftarLamaran = $user->pelamar->lamaran()->with('lowongan')->get();

        return Inertia::render('Pelamar/LamaranSaya', [
            'auth' => [
                'user' => $user->load('pelamar')
            ],
            'lamaran' => [] // Lempar data lamaran ke sini nanti
        ]);
    }
}