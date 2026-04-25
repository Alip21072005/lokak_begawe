<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\Lowongan;
use App\Models\Lamaran;
use App\Models\Mitra;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class DashboardMitraController extends Controller
{
    /**
     * Menampilkan halaman Dashboard utama untuk Mitra.
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Ambil data profil mitra yang terhubung dengan User ini
        // Kita gunakan eager loading untuk mengambil kategori dan lokasinya sekalian
        $mitra = Mitra::with(['kategori', 'lokasi'])
            ->where('user_id', $user->id)
            ->first();

        // Keamanan: Jika user punya role mitra tapi datanya belum ada di tabel mitras
        if (!$mitra) {
            return redirect()->route('welcome')->with('error', 'Profil mitra tidak ditemukan.');
        }

        // 1. STATISTIK UTAMA (Real-time)
        // Menghitung lowongan yang dibuat oleh mitra ini
        $totalLowongan = Lowongan::where('mitra_id', $mitra->id)->count();

        // Menghitung total pelamar yang masuk ke SEMUA lowongan milik mitra ini
        $totalPelamar = Lamaran::whereHas('lowongan', function ($query) use ($mitra) {
            $query->where('mitra_id', $mitra->id);
        })->count();

        // Menghitung lamaran yang statusnya masih 'pending' (Perlu di-review)
        $perluReview = Lamaran::whereHas('lowongan', function ($query) use ($mitra) {
            $query->where('mitra_id', $mitra->id);
        })->where('status', 'pending')->count();


        // 2. DATA PELAMAR TERBARU (Limit 5)
        // Mengambil data orang-orang yang baru saja melamar ke lowongan mitra ini
        $recentApplicants = Lamaran::with(['pelamar.user', 'lowongan'])
            ->whereHas('lowongan', function ($query) use ($mitra) {
                $query->where('mitra_id', $mitra->id);
            })
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($lamaran) {
                return [
                    'id' => $lamaran->id,
                    'nama_pelamar' => $lamaran->pelamar->user->name,
                    'posisi_dilamar' => $lamaran->lowongan->judul_lowongan,
                    'status' => $lamaran->status,
                    'tanggal' => $lamaran->created_at->diffForHumans(),
                    'avatar' => strtoupper(substr($lamaran->pelamar->user->name, 0, 1)),
                ];
            });

        return Inertia::render('Mitra/Dashboard', [
            'auth' => [
                'user' => $user,
                'mitra' => $mitra
            ],
            'stats' => [
                'total_lowongan' => $totalLowongan,
                'total_pelamar' => $totalPelamar,
                'perlu_review' => $perluReview,
            ],
            'recentApplicants' => $recentApplicants,
        ]);
    }
}