<?php

namespace App\Http\Controllers\Pelamar;

use App\Http\Controllers\Controller;
use App\Models\Lamaran;
use App\Models\Pelamar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class LamaranController extends Controller
{
    /**
     * Menampilkan daftar lamaran milik pelamar.
     */
    public function index(): Response
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->load('pelamar');

        $pelamar = $user->pelamar;
        $lamaranList = collect();

        if ($pelamar) {
            $lamaranList = Lamaran::with([
                'lowongan:id,judul_lowongan,mitra_id',
                'lowongan.mitra:id,nama_mitra,lokasi_id',
                'lowongan.mitra.lokasi:id,nama_lokasi',
            ])
                ->where('pelamar_id', $pelamar->pelamar_id)
                ->latest()
                ->get()
                ->map(function ($item) {
                    $status = strtolower((string) $item->status);

                    return [
                        'id' => $item->id,
                        'posisi' => $item->lowongan?->judul_lowongan ?? 'Posisi tidak tersedia',
                        'perusahaan' => $item->lowongan?->mitra?->nama_mitra ?? 'Perusahaan tidak tersedia',
                        'lokasi' => $item->lowongan?->mitra?->lokasi?->nama_lokasi ?? 'Bengkulu',
                        'tanggal_kirim' => $item->created_at?->translatedFormat('d M Y'),
                        'status' => $status ?: 'pending',
                        'catatan_mitra' => $item->catatan_mitra,
                        'created_at' => $item->created_at?->toDateTimeString(),
                    ];
                })
                ->values();
        }

        $summary = [
            'total' => $lamaranList->count(),
            'pending' => $lamaranList->where('status', 'pending')->count(),
            'reviewed' => $lamaranList->where('status', 'reviewed')->count(),
            'interview' => $lamaranList->where('status', 'interview')->count(),
            'accepted' => $lamaranList->where('status', 'accepted')->count(),
            'rejected' => $lamaranList->where('status', 'rejected')->count(),
        ];

        return Inertia::render('Pelamar/LamaranSaya', [
            'auth' => [
                'user' => $user,
            ],
            'lamaranList' => $lamaranList,
            'summary' => $summary, // optional, aman walau belum dipakai di Vue
        ]);
    }

    /**
     * Proses pengiriman lamaran baru dari detail lowongan.
     */
    public function store(Request $request, string $id): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $pelamar = Pelamar::where('user_id', $user->id)->firstOrFail();

        $alreadyApplied = Lamaran::where('pelamar_id', $pelamar->pelamar_id)
            ->where('lowongan_id', $id)
            ->exists();

        if ($alreadyApplied) {
            return back()->with(
                'error',
                'Kamu sudah pernah melamar di lowongan ini. Silakan pantau statusnya di halaman Lamaran Saya.'
            );
        }

        $validated = $request->validate([
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);

        Lamaran::create([
            'pelamar_id' => $pelamar->pelamar_id,
            'lowongan_id' => $id,
            'status' => 'pending',
            'catatan' => $validated['catatan'] ?? null,
        ]);

        return back()->with('success', 'Lamaran berhasil dikirim. Semoga sukses!');
    }
}