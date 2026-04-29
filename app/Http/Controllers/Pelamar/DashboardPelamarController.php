<?php

namespace App\Http\Controllers\Pelamar;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Lamaran;
use App\Models\Lokasi;
use App\Models\Mitra;
use App\Models\Pelamar;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class DashboardPelamarController extends Controller
{
    public function index(): Response|RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->load('pelamar');

        $pelamar = $user->pelamar ?: new Pelamar();

        $kolomPenting = [
            'nama_pelamar',
            'nohp_pelamar',
            'alamat_pelamar',
            'jenis_kelamin',
            'cv_pelamar',
            'foto_pelamar',
        ];

        $terisi = 0;
        foreach ($kolomPenting as $k) {
            if (!empty($pelamar->$k)) $terisi++;
        }

        $persentaseProfil = count($kolomPenting) > 0
            ? round(($terisi / count($kolomPenting)) * 100)
            : 0;

        $pelamarId = $pelamar->pelamar_id ?? '';

        $lamaranTerkirim = Lamaran::where('pelamar_id', $pelamarId)->count();
        $panggilanInterview = Lamaran::where('pelamar_id', $pelamarId)
            ->whereIn('status', ['interview', 'accepted'])
            ->count();

        $lamaranTerbaru = Lamaran::with(['lowongan.mitra'])
            ->where('pelamar_id', $pelamarId)
            ->latest()
            ->take(4)
            ->get();

        return Inertia::render('Pelamar/Dashboard', [
            'auth' => ['user' => $user],
            'persentaseProfil' => $persentaseProfil,
            'statistik' => [
                'lamaranTerkirim' => $lamaranTerkirim,
                'panggilanInterview' => $panggilanInterview,
                'pesanBaru' => 0,
                'lamaranDisimpan' => 0,
            ],
            'lamaranTerbaru' => $lamaranTerbaru,
        ]);
    }

    public function cariMitra(Request $request): Response|RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->load('pelamar');

        // Ambil filter dari request
        $search = $request->input('search');
        $lokasiId = $request->input('lokasi');
        $kategoriId = $request->input('kategori');

        $mitraList = Mitra::with(['lokasi', 'kategori'])
            ->where('status_mitra', 'verified')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_mitra', 'like', "%{$search}%")
                        ->orWhere('deskripsi_mitra', 'like', "%{$search}%")
                        ->orWhereHas('kategori', function ($sq) use ($search) {
                            $sq->where('nama_kategori', 'like', "%{$search}%");
                        });
                });
            })
            ->when($lokasiId, function ($query, $lokasiId) {
                $query->where('lokasi_id', $lokasiId);
            })
            ->when($kategoriId, function ($query, $kategoriId) {
                $query->where('kategori_id', $kategoriId);
            })
            ->latest()
            ->get()
            ->map(function ($m) {
                return [
                    'id' => $m->id,
                    'nama_mitra' => $m->nama_mitra,
                    'lokasi' => $m->lokasi?->nama_lokasi ?? 'Bengkulu',
                    'kategori' => $m->kategori?->nama_kategori ?? '-',
                    'lokasi_id' => $m->lokasi_id,
                    'kategori_id' => $m->kategori_id,
                    'deskripsi' => $m->deskripsi_mitra,
                    'logo_mitra' => $m->logo_mitra,
                ];
            })
            ->values();

        $lokasis = Lokasi::orderBy('nama_lokasi', 'asc')->get(['id', 'nama_lokasi']);
        $kategoris = Kategori::orderBy('nama_kategori', 'asc')->get(['id', 'nama_kategori']);

        return Inertia::render('Pelamar/CariMitra', [
            'auth' => ['user' => $user],
            'mitraList' => $mitraList,
            'lokasis' => $lokasis,
            'kategoris' => $kategoris,
            'filters' => [
                'search' => $search,
                'lokasi' => $lokasiId,
                'kategori' => $kategoriId,
            ],
        ]);
    }

    public function lihatProfilMitra(Request $request, string $id): Response|RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->load('pelamar');

        $mitra = Mitra::with(['lokasi', 'kategori', 'ratings.user'])
            ->with(['lowongan' => function ($q) {
                $q->where('status_lowongan', 'verified')->orderBy('created_at', 'desc');
            }])
            ->findOrFail($id);

        return Inertia::render('Pelamar/ProfileMitra', [
            'auth' => ['user' => $user],
            'partnerDetail' => [
                'id' => $mitra->id,
                'name' => $mitra->nama_mitra,
                'industry' => $mitra->kategori?->nama_kategori ?? 'Sektor Umum',
                'location' => $mitra->lokasi?->nama_lokasi ?? 'Bengkulu',
                'website' => $mitra->website_mitra,
                'founded' => $mitra->tahun_berdiri,
                'size' => $mitra->skala_perusahaan,
                'rating' => (string) round($mitra->ratings->avg('bintang') ?? 0, 1),
                'reviewCount' => $mitra->ratings->count(),
                'reviews' => $mitra->ratings->sortByDesc('created_at')->map(fn($rev) => [
                    'user_name' => $rev->user?->name ?? 'Anonim',
                    'bintang' => $rev->bintang,
                    'ulasan' => $rev->ulasan,
                    'date' => $rev->created_at->diffForHumans(),
                ])->values(),
                'description' => $mitra->deskripsi_mitra ?? 'Belum ada deskripsi.',
                'logo' => $mitra->logo_mitra ? asset('storage/' . $mitra->logo_mitra) : null,
                'banner' => $mitra->banner_mitra ? asset('storage/' . $mitra->banner_mitra) : null,
                'email' => $mitra->email_mitra,
                'phone' => $mitra->nohp_mitra,
                'address' => $mitra->alamat_mitra,
                'activeJobs' => $mitra->lowongan->map(fn($job) => [
                    'id' => $job->id,
                    'title' => $job->judul_lowongan,
                    'type' => $job->tipe_pekerjaan ?? 'Full Time',
                ]),
                'jobCount' => $mitra->lowongan->count(),
            ]
        ]);
    }
}
