<?php

namespace App\Http\Controllers\Pelamar;

use App\Http\Controllers\Controller;
use App\Models\Lowongan;
use App\Models\Lokasi;
use App\Models\Kategori;
use App\Models\Pelamar;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class LowongankerjaController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->load('pelamar');

        // 1. Ambil input filter
        $search = $request->input('search');
        $lokasiId = $request->input('lokasi');
        $kategoriId = $request->input('kategori');
        $gajiMin = $request->input('gaji_min');
        $gajiMax = $request->input('gaji_max');
        $sortBy = $request->input('sort', 'newest'); // newest, oldest, gaji_asc, gaji_desc

        // 2. Query utama dengan eager load skills
        $lowongan = Lowongan::with(['mitra.kategori', 'lokasi', 'mitra.lokasi', 'skills'])
            ->where('status_lowongan', 'verified')
            ->where('tanggal_expired', '>=', now()->toDateString())
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('judul_lowongan', 'like', "%{$search}%")
                        ->orWhere('deskripsi_lowongan', 'like', "%{$search}%")
                        ->orWhereHas('mitra', function ($sq) use ($search) {
                            $sq->where('nama_mitra', 'like', "%{$search}%")
                                ->orWhere('deskripsi_mitra', 'like', "%{$search}%");
                        })
                        ->orWhereHas('skills', function ($sq) use ($search) {
                            $sq->where('nama_skill', 'like', "%{$search}%");
                        });
                });
            })
            ->when($lokasiId, function ($query, $lokasiId) {
                $query->where('lokasi_id', $lokasiId);
            })
            ->when($kategoriId, function ($query, $kategoriId) {
                $query->whereHas('mitra', function ($q) use ($kategoriId) {
                    $q->where('kategori_id', $kategoriId);
                });
            })
            ->when($gajiMin, function ($query, $gajiMin) {
                $query->where(function ($q) use ($gajiMin) {
                    $q->where('gaji_min', '>=', $gajiMin)
                        ->orWhere(function ($sq) use ($gajiMin) {
                            $sq->whereNull('gaji_min')->where('gaji_max', '>=', $gajiMin);
                        });
                });
            })
            ->when($gajiMax, function ($query, $gajiMax) {
                $query->where(function ($q) use ($gajiMax) {
                    $q->where('gaji_max', '<=', $gajiMax)
                        ->orWhere(function ($sq) use ($gajiMax) {
                            $sq->whereNull('gaji_max')->where('gaji_min', '<=', $gajiMax);
                        });
                });
            })
            ->when($sortBy, function ($query, $sortBy) {
                switch ($sortBy) {
                    case 'oldest':
                        $query->orderBy('created_at', 'asc');
                        break;
                    case 'gaji_asc':
                        $query->orderByRaw('COALESCE(gaji_min, gaji_max, 0) asc');
                        break;
                    case 'gaji_desc':
                        $query->orderByRaw('COALESCE(gaji_max, gaji_min, 0) desc');
                        break;
                    case 'newest':
                    default:
                        $query->orderBy('created_at', 'desc');
                        break;
                }
            })
            ->get();

        // 3. Tarik data pendukung
        $lokasis = Lokasi::orderBy('nama_lokasi', 'asc')->get();
        $kategoris = Kategori::orderBy('nama_kategori', 'asc')->get();

        return Inertia::render('Pelamar/Lowongankerja', [
            'auth' => [
                'user' => $user ? $user->load('pelamar') : null
            ],
            'lowonganList' => $lowongan, // Kirim koleksi asli agar relasi mitra & lokasi tidak hilang
            'lokasis' => $lokasis,
            'kategoris' => $kategoris,
            'filters' => [
                'search' => $search,
                'lokasi' => $lokasiId,
                'kategori' => $kategoriId,
                'gaji_min' => $gajiMin,
                'gaji_max' => $gajiMax,
                'sort' => $sortBy,
            ]
        ]);
    }

    public function show(string $id): Response|RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->load('pelamar');

        $lowongan = Lowongan::with(['mitra.lokasi', 'mitra.kategori', 'lokasi', 'skills'])
            ->where('id', $id)
            ->where('status_lowongan', 'verified')
            ->firstOrFail();

        $pelamar = $user->pelamar ?? new Pelamar();

        return Inertia::render('Pelamar/DetailLowonganKerja', [
            'auth' => ['user' => $user],
            'authPelamar' => [
                'id' => $pelamar->pelamar_id,
                'nama_pelamar' => $pelamar->nama_pelamar ?? $user->name,
                'nohp_pelamar' => $pelamar->nohp_pelamar ?? '-',
            ],
            'lowongan' => [
                'id' => $lowongan->id,
                'judul' => $lowongan->judul_lowongan,
                'tipe' => $lowongan->tipe_pekerjaan ?? 'Full Time',
                'status' => $lowongan->status_lowongan,
                'lokasi_nama' => $lowongan->lokasi?->nama_lokasi ?? $lowongan->mitra?->lokasi?->nama_lokasi ?? 'Bengkulu',
                'deadline' => $lowongan->tanggal_expired ? date('d M Y', strtotime($lowongan->tanggal_expired)) : '-',
                'deskripsi' => $lowongan->deskripsi_lowongan ?? 'Tidak ada deskripsi.',
                'skills' => $lowongan->skills?->map(fn($s) => ['id' => $s->id, 'nama_skill' => $s->nama_skill])->values() ?? [],
                'minimal_pendidikan' => $lowongan->minimal_pendidikan ?? 'Tidak ditentukan',
                'minimal_pengalaman' => $lowongan->minimal_pengalaman ?? 0,
                'gaji_min' => $lowongan->gaji_min,
                'gaji_max' => $lowongan->gaji_max,
                'perusahaan' => [
                    'id' => $lowongan->mitra?->id,
                    'nama' => $lowongan->mitra?->nama_mitra ?? 'Perusahaan Mitra',
                    'logo' => $lowongan->mitra?->logo_mitra ? asset('storage/' . $lowongan->mitra->logo_mitra) : null,
                    'industri' => $lowongan->mitra?->kategori?->nama_kategori ?? 'Industri Umum',
                    'rating' => round($lowongan->mitra?->ratings?->avg('bintang') ?? 0, 1),
                    'is_verified' => $lowongan->mitra?->status_mitra === 'verified',
                    'alamat' => $lowongan->mitra?->alamat_mitra,
                    'website' => $lowongan->mitra?->website_mitra,
                    'email' => $lowongan->mitra?->email_mitra,
                    'deskripsi' => $lowongan->mitra?->deskripsi_mitra ?? 'Belum ada deskripsi perusahaan.',
                ],
            ],
        ]);
    }
}
