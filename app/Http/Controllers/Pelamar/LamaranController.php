<?php

namespace App\Http\Controllers\Pelamar;

use App\Http\Controllers\Controller;
use App\Models\Lamaran;
use App\Models\Pelamar;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class LamaranController extends Controller
{
    /**
     * Menampilkan daftar lamaran milik pelamar di Dashboard.
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->load('pelamar');

        $pelamar = $user->pelamar;
        $daftarLamaran = [];

        if ($pelamar) {
            // Ambil data lamaran beserta relasi lengkap
            $daftarLamaran = Lamaran::with(['lowongan.mitra.lokasi'])
                ->where('pelamar_id', $pelamar->pelamar_id)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($item) {
                    return [
                        'id'            => $item->id,
                        'posisi'        => $item->lowongan->judul_lowongan,
                        'perusahaan'    => $item->lowongan->mitra->nama_mitra,
                        'lokasi'        => $item->lowongan->mitra->lokasi->nama_lokasi ?? 'Bengkulu',
                        'tanggal_kirim' => $item->created_at->translatedFormat('d M Y'), // Format tanggal cantik
                        'status'        => $item->status, // pending, reviewed, interview, accepted, rejected
                        'catatan_mitra' => $item->catatan_mitra, // Pesan feedback dari HRD
                    ];
                });
        }

        return Inertia::render('Pelamar/LamaranSaya', [
            'auth' => [
                'user' => $user
            ],
            'lamaranList' => $daftarLamaran
        ]);
    }

    /**
     * Memproses pengiriman lamaran baru dari halaman Detail Lowongan (Modal Konfirmasi).
     */
    public function store(Request $request, $id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Ambil data pelamar berdasarkan user_id yang sedang login
        $pelamar = Pelamar::where('user_id', $user->id)->firstOrFail();

        // Validasi 1: Cek apakah pelamar sudah pernah melamar di lowongan ini sebelumnya
        $exists = Lamaran::where('pelamar_id', $pelamar->pelamar_id)
            ->where('lowongan_id', $id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Waduh! Kamu sudah melamar di lowongan ini sebelumnya. Silakan pantau statusnya di dashboard.');
        }

        // Validasi 2: Pastikan data input aman
        $request->validate([
            'catatan' => 'nullable|string|max:500',
        ]);

        // Simpan data lamaran ke database
        Lamaran::create([
            'pelamar_id'  => $pelamar->pelamar_id,
            'lowongan_id' => $id,
            'status'      => 'pending', // Status awal saat melamar
            'catatan'     => $request->catatan, // Pesan dari pelamar ke mitra
        ]);

        return back()->with('success', 'Selamat! Lamaran kamu berhasil terkirim. Semoga beruntung!');
    }
}