<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\User;
use App\Models\Mitra;
use App\Models\Lowongan;
use App\Models\Kategori;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardAdminController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user || ($user->role->value ?? $user->role) !== 'admin') {
            return redirect()->route('dashboard');
        }

        return Inertia::render('Admin/Dashboard', [
            'auth' => ['user' => $user],
            'counts' => $this->getStatsCounts(),
            'mitraPending' => $this->getPendingMitra(),
            'pelamarTerbaru' => User::where('role', 'pelamar')->latest()->take(6)->get(),
            'latestJobs' => Lowongan::with('mitra')->latest()->take(5)->get(),
            'registrationTrend' => $this->getTrendData(),
            'industryStats' => $this->getIndustryStats(),
            'activityLogs' => $this->getActivityLogs(),
        ]);
    }

    // --- FUNGSI BARU UNTUK HALAMAN DETAIL ---

    public function detailPelamar(string $id)
    {
        // Mengambil data pelamar
        $pelamar = User::findOrFail($id);

        // Pastikan nama file di dalam folder resources/js/Pages/Admin/ sesuai!
        return Inertia::render('Detail/Detailpelamar', [
            'pelamar' => $pelamar
        ]);
    }

    public function detailMitra(string $id)
    {
        // Mengambil data mitra beserta relasinya
        $mitra = Mitra::with(['kategori', 'lokasi', 'user'])->findOrFail($id);

        return Inertia::render('Admin/DetailVerivikasiMitra', [
            'mitra' => $mitra
        ]);
    }

    public function detailLowongan(string $id)
    {
        // Mengambil data lowongan beserta relasinya
        $lowongan = Lowongan::with(['mitra', 'lokasi'])->findOrFail($id);

        return Inertia::render('Admin/DetailVerifikasiLowongan', [
            'lowongan' => $lowongan
        ]);
    }

    // --- FUNGSI BAWAAN DASHBOARD ---

    private function getStatsCounts(): array
    {
        return [
            'pelamar' => User::where('role', 'pelamar')->count(),
            'mitra'   => Mitra::where('status_mitra', 'verified')->count(),
            'lowongan' => Lowongan::count(),
        ];
    }

    private function getPendingMitra()
    {
        return Mitra::where('status_mitra', 'pending')->with(['kategori', 'lokasi'])->latest()->get();
    }

    private function getTrendData(): array
    {
        $labels = [];
        $pelamar = [];
        $mitra = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->translatedFormat('d M');
            $pelamar[] = User::where('role', 'pelamar')->whereDate('created_at', $date->format('Y-m-d'))->count();
            $mitra[] = User::where('role', 'mitra')->whereDate('created_at', $date->format('Y-m-d'))->count();
        }
        return compact('labels', 'pelamar', 'mitra');
    }

    private function getIndustryStats()
    {
        return Kategori::withCount('mitra')->get()->map(fn($kat) => [
            'name' => $kat->nama_kategori,
            'count' => $kat->mitra_count
        ]);
    }

    private function getActivityLogs()
    {
        $u = User::latest()->take(3)->get()->map(fn($u) => ['desc' => "User baru: $u->name", 'time' => $u->created_at->diffForHumans()]);
        $j = Lowongan::latest()->take(3)->get()->map(fn($j) => ['desc' => "Lowongan: $j->judul_lowongan", 'time' => $j->created_at->diffForHumans()]);
        return $u->concat($j)->sortByDesc('time')->values()->take(5);
    }

    public function updateStatus(Request $request, Mitra $mitra)
    {
        $mitra->update(['status_mitra' => $request->status]);
        return back();
    }

    public function deletePelamar(string $id)
    {
        User::destroy($id);
        return back();
    }
}
