<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\Lamaran;
use App\Models\Lowongan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class KelolapelamarkerjaController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $mitra = $user->mitra;

        if (!$mitra) {
            return redirect()->route('mitra.dashboard')->with('error', 'Profil mitra tidak ditemukan.');
        }

        $search = trim((string) $request->input('search', ''));
        $status = trim((string) $request->input('status', ''));
        $lowonganId = trim((string) $request->input('lowongan_id', ''));

        $query = Lamaran::with([
            'pelamar.user',
            'lowongan:id,judul_lowongan,mitra_id',
        ])
            ->whereHas('lowongan', function ($q) use ($mitra) {
                $q->where('mitra_id', $mitra->id);
            })
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($nested) use ($search) {
                    $nested
                        ->whereHas('pelamar.user', function ($uq) use ($search) {
                            $uq->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        })
                        ->orWhereHas('lowongan', function ($lq) use ($search) {
                            $lq->where('judul_lowongan', 'like', "%{$search}%");
                        });
                });
            })
            ->when($status !== '', function ($q) use ($status) {
                $q->where('status', $status);
            })
            ->when($lowonganId !== '', function ($q) use ($lowonganId) {
                $q->where('lowongan_id', $lowonganId);
            })
            ->latest();

        $lamarans = $query->get();

        $formatted = $lamarans->map(function ($item) {
            $pelamarUser = $item->pelamar?->user;

            return [
                'id' => $item->id,
                'lowongan_id' => $item->lowongan_id,
                'lowongan_title' => $item->lowongan?->judul_lowongan ?? 'Lowongan Tidak Diketahui',
                'user_id' => $pelamarUser?->id,
                'name' => $pelamarUser?->name ?? 'Pelamar',
                'email' => $pelamarUser?->email ?? '-',
                'phone' => $item->pelamar?->nohp_pelamar ?? '-',
                'status' => $item->status ?? 'pending',
                'catatan_mitra' => $item->catatan_mitra,
                'applied_date' => $item->created_at?->format('d M Y'),
                'applied_human' => $item->created_at?->diffForHumans(),
                'avatar' => strtoupper(substr(($pelamarUser?->name ?? 'P'), 0, 1)),
                'cv' => $item->pelamar?->cv_pelamar ? asset('storage/' . $item->pelamar->cv_pelamar) : null,
            ];
        });

        $groupedApplicants = $formatted
            ->groupBy('lowongan_id')
            ->map(function ($items, $groupLowonganId) {
                $first = $items->first();
                $statusCount = [
                    'pending' => $items->where('status', 'pending')->count(),
                    'reviewed' => $items->where('status', 'reviewed')->count(),
                    'interview' => $items->where('status', 'interview')->count(),
                    'accepted' => $items->where('status', 'accepted')->count(),
                    'rejected' => $items->where('status', 'rejected')->count(),
                ];

                return [
                    'lowongan_id' => $groupLowonganId,
                    'lowongan_title' => $first['lowongan_title'],
                    'total' => $items->count(),
                    'status_count' => $statusCount,
                    'applicants' => $items->values(),
                ];
            })
            ->sortByDesc('total')
            ->values();

        $allLowonganOptions = Lowongan::query()
            ->where('mitra_id', $mitra->id)
            ->orderByDesc('created_at')
            ->get(['id', 'judul_lowongan'])
            ->map(fn ($l) => [
                'id' => (string) $l->id,
                'title' => $l->judul_lowongan,
            ])
            ->values();

        $overview = [
            'total_lamaran' => $formatted->count(),
            'total_lowongan' => $groupedApplicants->count(),
            'pending' => $formatted->where('status', 'pending')->count(),
            'interview' => $formatted->where('status', 'interview')->count(),
            'accepted' => $formatted->where('status', 'accepted')->count(),
        ];

        return Inertia::render('Mitra/Kelolapelamarkerja', [
            'auth' => ['user' => $user->load('mitra')],
            'groupedApplicants' => $groupedApplicants,
            'filters' => [
                'search' => $search,
                'status' => $status,
                'lowongan_id' => $lowonganId,
            ],
            'lowonganOptions' => $allLowonganOptions,
            'overview' => $overview,
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,reviewed,interview,accepted,rejected',
            'catatan_mitra' => 'nullable|string|max:500',
        ]);

        $lamaran = Lamaran::with('pelamar.user')->findOrFail($id);

        $lamaran->update([
            'status' => $request->status,
            'catatan_mitra' => $request->catatan_mitra,
        ]);

        $name = $lamaran->pelamar?->user?->name ?? 'pelamar';

        return redirect()->back()->with('success', "Status pelamar {$name} berhasil diperbarui.");
    }
}