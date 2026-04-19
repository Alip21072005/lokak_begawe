<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mitra;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class KelolamitraController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->input('search');

        // Query dasar dengan relasi kategori
        $query = Mitra::with('kategori')
            ->when($search, function ($q, $search) {
                $q->where('nama_mitra', 'like', "%{$search}%")
                    ->orWhere('email_mitra', 'like', "%{$search}%");
            });

        // Pisahkan data untuk Tab
        $mitraPending = (clone $query)->where('status_mitra', 'pending')->latest()->get();
        $mitraActive = (clone $query)->where('status_mitra', 'verified')->latest()->get();

        // Statistik
        $stats = [
            'total' => Mitra::count(),
            'pending' => Mitra::where('status_mitra', 'pending')->count(),
            'new_this_month' => Mitra::whereMonth('created_at', Carbon::now()->month)->count(),
        ];

        return Inertia::render('Admin/Kelolamitra', [
            'auth' => ['user' => $user],
            'mitraPending' => $mitraPending,
            'mitraActive' => $mitraActive,
            'stats' => $stats,
            'filters' => ['search' => $search]
        ]);
    }
}