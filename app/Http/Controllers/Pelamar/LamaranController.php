<?php

namespace App\Http\Controllers\Pelamar;

use App\Http\Controllers\Controller;
use App\Models\Lamaran;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class LamaranController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->load('pelamar');

        $pelamar = $user->pelamar;
        $daftarLamaran = [];

        // Pastikan user sudah punya profil pelamar sebelum menarik data
        if ($pelamar) {
            // Tarik data lamaran beserta relasi ke lowongan, mitra, dan lokasi
            $daftarLamaran = Lamaran::with(['lowongan.mitra.lokasi'])
                ->where('pelamar_id', $pelamar->pelamar_id)
                ->orderBy('created_at', 'desc') // Urutkan dari yang paling baru dilamar
                ->get();
        }

        // CATATAN: Pastikan nama 'Pelamar/Lamaran' sesuai dengan nama file Vue kamu di resources/js/Pages/Pelamar/...
        // Bisa jadi namanya Lamaran.vue atau LamaranSaya.vue. Sesuaikan teks di bawah ini:
        return Inertia::render('Pelamar/LamaranSaya', [
            'auth' => [
                'user' => $user
            ],
            'lamaranList' => $daftarLamaran
        ]);
    }
}