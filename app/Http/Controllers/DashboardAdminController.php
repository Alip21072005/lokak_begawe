<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Mitra;
use App\Models\Lowongan;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class DashboardAdminController extends Controller
{
    public function index(): Response|RedirectResponse
    {
        $user = Auth::user();
        $role = $user?->role instanceof \UnitEnum ? $user->role->value : $user?->role;

        if (!$user || $role !== 'admin') {
            return redirect()->route('dashboard');
        }

        return Inertia::render('Admin/Dashboard', [
            'auth' => ['user' => $user],
            'counts' => $this->getStatsCounts(),
            'mitraPending' => $this->getPendingMitra(),
            'pelamarTerbaru' => $this->getLatestApplicants(),
            'latestJobs' => $this->getLatestJobs(),
            'registrationTrend' => $this->getRegistrationTrend(),
            'industryStats' => $this->getIndustryStats(),
            'activityLogs' => $this->getActivityLogs(),
        ]);
    }

    public function detailPelamar(string $id): Response
    {
        $pelamar = User::with('pelamar')->findOrFail($id);

        return Inertia::render('Detail/Detailpelamar', [
            'pelamar' => $pelamar->pelamar ?? $pelamar,
        ]);
    }

    public function detailMitra(string $id): Response
    {
        $mitra = Mitra::with(['kategori', 'lokasi', 'user'])->findOrFail($id);

        return Inertia::render('Admin/DetailVerivikasiMitra', [
            'mitra' => $mitra,
        ]);
    }

    /**
     * FIX UTAMA:
     * - load relasi transaksi
     * - kirim field yang dipakai frontend:
     *   bukti_pembayaran, paket_pembayaran, status_lowongan, dll
     */
    public function detailLowongan(string $id): Response
    {
        $lowongan = Lowongan::with(['mitra', 'lokasi', 'transaksi'])->findOrFail($id);

        return Inertia::render('Admin/DetailVerifikasiLowongan', [
            'lowongan' => [
                'id' => $lowongan->id,
                'judul_lowongan' => $lowongan->judul_lowongan,
                'deskripsi_lowongan' => $lowongan->deskripsi_lowongan,
                'tipe_pekerjaan' => $lowongan->tipe_pekerjaan,
                'gaji_min' => $lowongan->gaji_min,
                'gaji_max' => $lowongan->gaji_max,
                'status_lowongan' => $lowongan->status_lowongan,
                'created_at' => $lowongan->created_at,

                'mitra' => [
                    'nama_mitra' => $lowongan->mitra?->nama_mitra,
                    'email_mitra' => $lowongan->mitra?->email_mitra,
                    'alamat_mitra' => $lowongan->mitra?->alamat_mitra,
                ],

                'lokasi' => [
                    'nama_lokasi' => $lowongan->lokasi?->nama_lokasi,
                ],

                // mapping transaksi agar terbaca Vue
                'paket_pembayaran' => $lowongan->transaksi?->nama_paket,
                'status_pembayaran' => $lowongan->transaksi?->status_pembayaran ?? 'unpaid',
                'bukti_pembayaran' => $lowongan->transaksi?->bukti_transfer
                    ? asset('storage/' . $lowongan->transaksi->bukti_transfer)
                    : null,
            ],
        ]);
    }

    public function updateStatus(Request $request, Mitra $mitra): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,verified,rejected'],
        ]);

        $mitra->update([
            'status_mitra' => $validated['status'],
        ]);

        return back()->with('status', 'Status mitra berhasil diperbarui.');
    }

    public function deletePelamar(string $id): RedirectResponse
    {
        $user = User::findOrFail($id);
        $user->delete();

        return back()->with('status', 'Pelamar berhasil dihapus.');
    }

    public function blockPelamar(Request $request, string $id): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['nullable', 'in:active,blocked'],
        ]);

        $user = User::where('role', 'pelamar')->findOrFail($id);
        $user->update([
            'status' => $validated['status'] ?? 'blocked',
        ]);

        return back()->with('status', 'Status pelamar berhasil diperbarui.');
    }

    private function getStatsCounts(): array
    {
        return [
            'pelamar' => User::where('role', 'pelamar')->count(),
            'mitra' => Mitra::where('status_mitra', 'verified')->count(),
            'lowongan' => Lowongan::count(),
        ];
    }

    private function getPendingMitra()
    {
        return Mitra::with(['kategori', 'lokasi', 'user'])
            ->where('status_mitra', 'pending')
            ->latest()
            ->take(8)
            ->get()
            ->map(function ($m) {
                return [
                    'id' => $m->id,
                    'nama_mitra' => $m->nama_mitra,
                    'kategori' => $m->kategori?->nama_kategori,
                    'lokasi' => $m->lokasi?->nama_lokasi,
                    'created_at' => $m->created_at?->toDateTimeString(),
                ];
            })
            ->values();
    }

    private function getLatestApplicants()
    {
        return User::query()
            ->where('role', 'pelamar')
            ->latest()
            ->take(8)
            ->get(['id', 'name', 'email', 'created_at']);
    }

    private function getLatestJobs()
    {
        return Lowongan::query()
            ->with(['mitra:id,nama_mitra'])
            ->latest()
            ->take(8)
            ->get()
            ->map(function ($job) {
                return [
                    'id' => $job->id,
                    'judul_lowongan' => $job->judul_lowongan,
                    'created_at' => $job->created_at?->toDateTimeString(),
                    'mitra' => [
                        'nama_mitra' => $job->mitra?->nama_mitra ?? 'Mitra',
                    ],
                ];
            })
            ->values();
    }

    private function getRegistrationTrend(): array
    {
        $labels = [];
        $pelamar = [];
        $mitra = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->startOfDay();
            $labels[] = $date->translatedFormat('d M');

            $pelamar[] = User::where('role', 'pelamar')
                ->whereDate('created_at', $date)
                ->count();

            $mitra[] = User::where('role', 'mitra')
                ->whereDate('created_at', $date)
                ->count();
        }

        return [
            'labels' => $labels,
            'pelamar' => $pelamar,
            'mitra' => $mitra,
        ];
    }

    private function getIndustryStats()
    {
        return Kategori::query()
            ->withCount('mitra')
            ->orderByDesc('mitra_count')
            ->take(12)
            ->get()
            ->map(function ($kat) {
                return [
                    'name' => $kat->nama_kategori,
                    'count' => (int) $kat->mitra_count,
                ];
            })
            ->values();
    }

    private function getActivityLogs()
    {
        $userLogs = User::query()
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($u) {
                return [
                    'desc' => 'User baru: ' . $u->name,
                    'time' => $u->created_at?->diffForHumans(),
                    'sort_at' => $u->created_at,
                ];
            });

        $jobLogs = Lowongan::query()
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($j) {
                return [
                    'desc' => 'Lowongan baru: ' . $j->judul_lowongan,
                    'time' => $j->created_at?->diffForHumans(),
                    'sort_at' => $j->created_at,
                ];
            });

        $mitraLogs = Mitra::query()
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($m) {
                return [
                    'desc' => 'Mitra mendaftar: ' . $m->nama_mitra,
                    'time' => $m->created_at?->diffForHumans(),
                    'sort_at' => $m->created_at,
                ];
            });

        return $userLogs
            ->concat($jobLogs)
            ->concat($mitraLogs)
            ->sortByDesc('sort_at')
            ->take(10)
            ->map(fn ($item) => [
                'desc' => $item['desc'],
                'time' => $item['time'],
            ])
            ->values();
    }
}