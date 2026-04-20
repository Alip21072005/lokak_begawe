<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\Lamaran;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class KelolapelamarkerjaController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $mitra = $user->mitra;

        // Ambil data lamaran yang masuk ke lowongan milik mitra ini
        $applicants = Lamaran::with(['pelamar.user', 'lowongan'])
            ->whereHas('lowongan', function ($q) use ($mitra) {
                $q->where('mitra_id', $mitra->id);
            })
            ->when($request->search, function ($query, $search) {
                $query->whereHas('pelamar.user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->get()
            ->map(function ($item) {
                // Logika skor kecocokan sederhana (nanti bisa kamu kembangkan)
                // Sementara kita buat random 60-95 agar terlihat dinamis di UI
                $score = rand(60, 95);

                return [
                    'id' => $item->id,
                    'name' => $item->pelamar->user->name,
                    'position' => $item->lowongan->judul_lowongan,
                    'matchScore' => $score,
                    'appliedDate' => $item->created_at->format('d M Y'),
                    'status' => $item->status, // 'pending', 'interview', 'rejected'
                    'avatar' => strtoupper(substr($item->pelamar->user->name, 0, 1)),
                ];
            });

        return Inertia::render('Mitra/Kelolapelamarkerja', [
            'auth' => ['user' => $user->load('mitra')],
            'applicants' => $applicants,
            'filters' => $request->only(['search'])
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $lamaran = Lamaran::findOrFail($id);
        $lamaran->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status pelamar berhasil diperbarui.');
    }
}