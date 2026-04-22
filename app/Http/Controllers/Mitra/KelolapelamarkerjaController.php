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
                return [
                    'id'            => $item->id,
                    'name'          => $item->pelamar->user->name,
                    'position'      => $item->lowongan->judul_lowongan,
                    'appliedDate'   => $item->created_at->format('d M Y'),
                    'status'        => $item->status,
                    'catatan_mitra' => $item->catatan_mitra,
                    'avatar'        => strtoupper(substr($item->pelamar->user->name, 0, 1)),
                    // Data tambahan untuk modal detail di sisi mitra
                    'email'         => $item->pelamar->user->email,
                    'phone'         => $item->pelamar->nohp_pelamar,
                    'cv'            => $item->pelamar->cv_pelamar ? asset('storage/' . $item->pelamar->cv_pelamar) : null,
                ];
            });

        return Inertia::render('Mitra/Kelolapelamarkerja', [
            'auth'       => ['user' => $user->load('mitra')],
            'applicants' => $applicants,
            'filters'    => $request->only(['search'])
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status'        => 'required|in:pending,reviewed,interview,accepted,rejected',
            'catatan_mitra' => 'nullable|string|max:500',
        ]);

        $lamaran = Lamaran::findOrFail($id);

        $lamaran->update([
            'status'        => $request->status,
            'catatan_mitra' => $request->catatan_mitra
        ]);

        // Opsional: Kamu bisa kirim notifikasi email/pesan sistem di sini nantinya

        return redirect()->back()->with('success', 'Status pelamar ' . $lamaran->pelamar->user->name . ' berhasil diperbarui.');
    }
}