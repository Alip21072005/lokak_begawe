<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lowongan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class KelolalowonganController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->input('search');

        $jobs = Lowongan::with(['mitra', 'lokasi', 'transaksi'])
            ->when($search, function ($query, $search) {
                $query->where('judul_lowongan', 'like', "%{$search}%")
                    ->orWhereHas('mitra', function ($q) use ($search) {
                        $q->where('nama_mitra', 'like', "%{$search}%");
                    });
            })
            ->latest()
            ->get()
            ->map(function ($job) {
                return [
                    'id' => $job->id,
                    'judul_lowongan' => $job->judul_lowongan,
                    'mitra_nama' => $job->mitra->nama_mitra ?? 'Anonim',
                    'lokasi_nama' => $job->lokasi->nama_lokasi ?? 'Bengkulu',
                    'gaji' => 'Rp ' . number_format($job->gaji_min, 0, ',', '.') . ' - ' . number_format($job->gaji_max, 0, ',', '.'),
                    'status' => $job->status_lowongan,
                    'pembayaran_status' => $job->transaksi->status_pembayaran ?? 'unpaid',
                    'created_at' => $job->created_at->format('Y-m-d'),
                ];
            });

        return Inertia::render('Admin/Kelolalowongan', [
            'auth' => ['user' => $user],
            'jobs' => $jobs,
            'filters' => ['search' => $search]
        ]);
    }

    public function show(string $id)
    {
        $lowongan = Lowongan::with(['mitra', 'lokasi', 'transaksi'])->findOrFail($id);

        return Inertia::render('Admin/DetailVerifikasiLowongan', [
            'job' => [
                'id' => $lowongan->id,
                'judul' => $lowongan->judul_lowongan,
                'tipe' => $lowongan->tipe_pekerjaan,
                'deskripsi' => $lowongan->deskripsi_lowongan,
                'gaji' => 'Rp ' . number_format($lowongan->gaji_min, 0, ',', '.') . ' - ' . number_format($lowongan->gaji_max, 0, ',', '.'),
                'lokasi' => $lowongan->lokasi->nama_lokasi ?? 'Bengkulu',
                'status' => $lowongan->status_lowongan,
                'mitra' => [
                    'nama' => $lowongan->mitra->nama_mitra,
                    'email' => $lowongan->mitra->email_mitra,
                    'alamat' => $lowongan->mitra->alamat_mitra,
                ],
                'pembayaran' => $lowongan->transaksi ? [
                    'paket' => $lowongan->transaksi->nama_paket,
                    'harga' => $lowongan->transaksi->harga,
                    'bukti' => asset('storage/' . $lowongan->transaksi->bukti_transfer),
                    'status' => $lowongan->transaksi->status_pembayaran,
                ] : null,
            ]
        ]);
    }

    public function updateStatus(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|in:verified,rejected,pending'
        ]);

        $lowongan = Lowongan::findOrFail($id);

        // Update status lowongan
        $lowongan->update(['status_lowongan' => $request->status]);

        // Jika disetujui, otomatis setujui transaksinya juga jika ada
        if ($lowongan->transaksi) {
            $lowongan->transaksi->update([
                'status_pembayaran' => $request->status === 'verified' ? 'verified' : 'rejected'
            ]);
        }

        return redirect()->route('admin.kelolalowongan')->with('success', 'Status Berhasil Diperbarui');
    }

    public function destroy(string $id)
    {
        Lowongan::findOrFail($id)->delete();
        return redirect()->back();
    }
}
