<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class KelolapelamarController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Ambil query pencarian
        $search = $request->input('search');

        // Query data pelamar
        $pelamarQuery = User::where('role', 'pelamar')
            ->with(['pelamar' => function ($query) {
                $query->withCount('lamaran'); // Hitung jumlah lamaran
            }])
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->latest();

        // Statistik
        $totalPelamar = User::where('role', 'pelamar')->count();
        $aktifBulanIni = User::where('role', 'pelamar')
            ->whereMonth('created_at', now()->month)
            ->count();

        return Inertia::render('Admin/Kelolapelamar', [
            'auth' => ['user' => $user],
            'filters' => ['search' => $search],
            'users' => $pelamarQuery->get()->map(function ($u) {
                return [
                    'id' => $u->id,
                    'name' => $u->name,
                    'email' => $u->email,
                    'status' => $u->status ?? 'active',
                    'phone' => $u->pelamar?->nohp_pelamar ?? '-',
                    'university' => $u->pelamar?->alamat_pelamar ?? 'Belum Mengisi Profil',
                    'applicationsCount' => $u->pelamar?->lamaran_count ?? 0,
                    'avatar' => strtoupper(substr($u->name, 0, 2)),
                ];
            }),
            'stats' => [
                'total' => $totalPelamar,
                'active' => $aktifBulanIni
            ]
        ]);
    }
}