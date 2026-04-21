<?php

namespace App\Http\Controllers\Pelamar;

use App\Http\Controllers\Controller;
use App\Models\Lamaran;
use App\Models\Mitra;
use App\Models\Pelamar;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class DashboardPelamarController extends Controller
{
    public function index()
    {
        // 1. Ambil data User beserta relasi Pelamar
        $user = Auth::user();
        $user->load('pelamar');

        $pelamar = $user->pelamar;

        // Jika pelamar belum ada
        if (!$pelamar) {
            $pelamar = new Pelamar();
        }

        // 2. Persentase Profil
        $kolomPenting = ['nama_pelamar', 'nohp_pelamar', 'alamat_pelamar', 'jenis_kelamin', 'cv_pelamar', 'foto_pelamar'];
        $kolomTerisi = 0;

        foreach ($kolomPenting as $kolom) {
            if (!empty($pelamar->$kolom)) {
                $kolomTerisi++;
            }
        }
        $persentaseProfil = count($kolomPenting) > 0 ? round(($kolomTerisi / count($kolomPenting)) * 100) : 0;

        // 3. Statistik Lamaran
        $lamaranTerkirim = Lamaran::where('pelamar_id', $pelamar->pelamar_id ?? '')->count();
        $panggilanInterview = Lamaran::where('pelamar_id', $pelamar->pelamar_id ?? '')
            ->where('status', 'accepted') // sesuai status database mu
            ->count();

        // 4. Lamaran Terbaru
        $lamaranTerbaru = Lamaran::with(['lowongan.mitra'])
            ->where('pelamar_id', $pelamar->pelamar_id ?? '')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        // 5. Mitra Terverifikasi (Sesuaikan 'status_mitra' dengan yang ada di db kamu, misal 'aktif' atau 'verified')
        $mitraVerified = Mitra::with('lokasi')
            ->where('status_mitra', 'verified')
            ->inRandomOrder()
            ->take(4)
            ->get();

        // Mengirim data ke Vue Inertia
        return Inertia::render('Pelamar/Dashboard', [
            'auth' => ['user' => $user],
            'persentaseProfil' => $persentaseProfil,
            'statistik' => [
                'lamaranTerkirim' => $lamaranTerkirim,
                'panggilanInterview' => $panggilanInterview,
                'pesanBaru' => 0, // Default 0
                'lamaranDisimpan' => 0, // Default 0
            ],
            'lamaranTerbaru' => $lamaranTerbaru,
            'mitraVerified' => $mitraVerified,
        ]);
    }
}
