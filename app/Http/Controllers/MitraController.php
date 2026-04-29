<?php

namespace App\Http\Controllers;

use App\Models\Mitra;
use App\Models\Lokasi;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\Rating;

class MitraController extends Controller
{
    public function index(Request $request)
    {
        $query = Mitra::with(['lokasi', 'kategori'])
            ->withCount(['lowongan' => function ($q) {
                $q->where('status_lowongan', 'verified');
            }])
            ->withCount('ratings')
            ->withAvg('ratings', 'bintang');

        if ($request->search) $query->where('nama_mitra', 'like', '%' . $request->search . '%');
        if ($request->lokasi) $query->where('lokasi_id', $request->lokasi);
        if ($request->kategori) $query->where('kategori_id', $request->kategori);

        $mitras = $query->orderBy('ratings_count', 'desc')->get()->map(fn($m) => [
            'id' => (string) $m->id,
            'name' => $m->nama_mitra,
            'location' => $m->lokasi->nama_lokasi ?? 'Bengkulu',
            'industry' => $m->kategori->nama_kategori ?? 'Umum',
            'rating' => (string) round($m->ratings_avg_bintang ?? 0, 1),
            'review_count' => $m->ratings_count,
            'logo' => $m->logo_mitra ? asset('storage/' . $m->logo_mitra) : null,
        ]);

        return Inertia::render('Mitra', [
            'mitras' => $mitras,
            'lokasis' => Lokasi::orderBy('nama_lokasi', 'asc')->get(),
            'kategoris' => Kategori::orderBy('nama_kategori', 'asc')->get(),
            'filters' => $request->only(['search', 'lokasi', 'kategori']),
        ]);
    }

    public function show($id)
    {
        $mitra = Mitra::with(['lokasi', 'kategori', 'ratings.user'])
            ->with(['lowongan' => function ($q) {
                // Hanya ambil lowongan yang statusnya verified
                $q->where('status_lowongan', 'verified')->orderBy('created_at', 'desc');
            }])
            ->findOrFail($id);

        return Inertia::render('Detail/Detailmitra', [
            'partnerDetail' => [
                'id' => $mitra->id,
                'name' => $mitra->nama_mitra,
                'industry' => $mitra->kategori->nama_kategori ?? 'Sektor Umum',
                'location' => $mitra->lokasi->nama_lokasi ?? 'Bengkulu',
                'website' => $mitra->website_mitra,
                'founded' => $mitra->tahun_berdiri,
                'size' => $mitra->skala_perusahaan,
                'rating' => (string) round($mitra->ratings->avg('bintang') ?? 0, 1),
                'reviewCount' => $mitra->ratings->count(),
                'reviews' => $mitra->ratings->sortByDesc('created_at')->map(fn($rev) => [
                    'user_name' => $rev->user->name ?? 'Anonim',
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

    public function storeRating(Request $request, $id)
    {
        $request->validate(['bintang' => 'required|integer|min:1|max:5', 'ulasan' => 'required|string|min:5']);
        Rating::updateOrCreate(['user_id' => Auth::id(), 'mitra_id' => $id], $request->only(['bintang', 'ulasan']));
        return back();
    }
}