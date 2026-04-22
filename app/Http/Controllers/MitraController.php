<?php

namespace App\Http\Controllers;

use App\Models\Mitra;
use App\Models\Lokasi;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MitraController extends Controller
{
    public function index(Request $request)
    {
        $query = Mitra::with(['lokasi', 'kategori', 'lowongan']);

        // Filter Nama
        if ($request->search) {
            $query->where('nama_mitra', 'like', '%' . $request->search . '%');
        }

        // Filter Lokasi (ID)
        if ($request->lokasi) {
            $query->where('lokasi_id', $request->lokasi);
        }

        // Filter Kategori (ID)
        if ($request->kategori) {
            $query->where('kategori_id', $request->kategori);
        }

        $mitras = $query->latest()->get()->map(function ($mitra) {
            return [
                'id' => (string) $mitra->id,
                'name' => $mitra->nama_mitra,
                'location' => $mitra->lokasi->nama_lokasi ?? 'Provinsi Bengkulu',
                'industry' => $mitra->kategori->nama_kategori ?? 'Umum',
                'jobs_count' => $mitra->lowongan->count() . ' Lowongan',
                'rating' => '4.5',
                'logo' => $mitra->logo_mitra ? asset('storage/' . $mitra->logo_mitra) : null,
            ];
        });

        return Inertia::render('Mitra', [
            'mitras' => $mitras,
            'lokasis' => Lokasi::orderBy('nama_lokasi', 'asc')->get(),
            'kategoris' => Kategori::orderBy('nama_kategori', 'asc')->get(),
            'filters' => $request->only(['search', 'lokasi', 'kategori']),
        ]);
    }

    public function show($id)
    {
        // Ambil mitra beserta relasi lokasi, kategori, dan semua lowongan
        $mitra = Mitra::with(['lokasi', 'kategori', 'lowongan'])->findOrFail($id);

        return Inertia::render('Detail/Detailmitra', [
            'partnerDetail' => [
                'id' => $mitra->id,
                'name' => $mitra->nama_mitra,
                'industry' => $mitra->kategori->nama_kategori ?? 'Sektor Umum',
                'location' => $mitra->lokasi->nama_lokasi ?? 'Bengkulu, Indonesia',
                'website' => $mitra->website_mitra ?? 'Tidak tersedia',
                'founded' => $mitra->tahun_berdiri ?? '-', // Dinamis dari kolom baru
                'size' => $mitra->skala_perusahaan ?? '1-50 Karyawan', // Dinamis dari kolom baru
                'rating' => '4.8', // Bisa dibuat dinamis nanti jika ada tabel rating
                'reviewCount' => '154',
                'jobCount' => $mitra->lowongan->count() . ' LOWONGAN AKTIF',
                'description' => $mitra->deskripsi_mitra ?? 'Perusahaan ini belum menambahkan deskripsi profil.',
                'logo' => $mitra->logo_mitra ? asset('storage/' . $mitra->logo_mitra) : null,
                'email' => $mitra->email_mitra,
                'address' => $mitra->alamat_mitra,

                // Lowongan yang sedang dibuka oleh mitra ini
                'activeJobs' => $mitra->lowongan->map(fn($job) => [
                    'id' => $job->id,
                    'title' => $job->judul_lowongan,
                    'type' => $job->tipe_pekerjaan ?? 'Full Time',
                    'salary' => 'Kompetitif', // Bisa diambil dari kolom gaji jika ada
                ]),
            ]
        ]);
    }
}