<?php

namespace App\Http\Controllers;

use App\Models\Mitra; 
use Illuminate\Http\Request;
use Inertia\Inertia;

class MitraController extends Controller
{
    /**
     * Menampilkan daftar semua mitra (Halaman Index)
     */
    public function index(Request $request)
    {
        // Ambil data dari tabel mitras dengan relasi
        $query = Mitra::with(['lokasi', 'lowongan']);

        // Filter berdasarkan NAMA MITRA
        if ($request->search) {
            $query->where('nama_mitra', 'like', '%' . $request->search . '%');
        }

        // Filter berdasarkan LOKASI (lewat relasi lokasi)
        if ($request->lokasi) {
            $query->whereHas('lokasi', function ($q) use ($request) {
                $q->where('nama_lokasi', 'like', '%' . $request->lokasi . '%');
            });
        }

        $mitras = $query->latest()->get()->map(function ($mitra) {
            return [
                'id' => (string) $mitra->id,
                'name' => $mitra->nama_mitra,
                'location' => $mitra->lokasi->nama_lokasi ?? 'Provinsi Bengkulu',
                'jobs_count' => $mitra->lowongan->count() . ' Lowongan',
                'rating' => '4.5', // Bisa dinamis jika ada tabel rating
                'logo' => $mitra->logo_mitra ? asset('storage/' . $mitra->logo_mitra) : null,
            ];
        });

        return Inertia::render('Mitra', [
            'mitras' => $mitras,
            'filters' => $request->only(['search', 'lokasi']),
        ]);
    }

    /**
     * Menampilkan detail satu mitra (Halaman Detailmitra)
     */
    public function show($id)
    {
        // Ambil data mitra spesifik beserta relasi lokasi dan semua lowongannya
        $mitra = Mitra::with(['lokasi', 'lowongan'])->findOrFail($id);

        return Inertia::render('Detailmitra', [
            'partnerDetail' => [
                'name' => $mitra->nama_mitra,
                'industry' => $mitra->industri ?? 'Sektor Umum',
                'location' => $mitra->lokasi->nama_lokasi ?? 'Bengkulu, Indonesia',
                'website' => $mitra->website ?? 'Tidak tersedia',
                'founded' => $mitra->tahun_berdiri ?? '-',
                'size' => $mitra->skala_perusahaan ?? '1-50 Karyawan',
                'rating' => '4.5 (154)', // Angka static sesuai design awal
                'jobCount' => $mitra->lowongan->count() . ' PEKERJAAN',
                'description' => $mitra->deskripsi ?? 'Perusahaan ini belum menambahkan deskripsi profil.',
                'logo' => $mitra->logo_mitra ? asset('storage/' . $mitra->logo_mitra) : null,
                
                // Data Lowongan Aktif untuk bagian list di bawah detail
                'activeJobs' => $mitra->lowongan->map(fn($job) => [
                    'id' => $job->id,
                    'title' => $job->judul_lowongan,
                    'type' => $job->tipe_pekerjaan ?? 'Full Time',
                    'salary' => $job->gaji ?? 'Kompetitif',
                ]),
            ]
        ]);
    }
}