<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\User;
use App\Models\Mitra;
use App\Models\Lowongan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardAdminController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 1. Proteksi Role (Admin Only)
        $role = $user->role instanceof \UnitEnum ? $user->role->value : $user->role;
        if ($role !== 'admin') {
            return redirect()->route('dashboard');
        }

        return Inertia::render('Admin/Dashboard', [
            'auth' => [
                'user' => $user
            ],

            // 2. Statistik
            'counts' => [
                'pelamar' => User::where('role', 'pelamar')->count(),
                'mitra'   => Mitra::where('status_mitra', 'verified')->count(),
                'lowongan' => Lowongan::count(),
            ],

            // 3. Antrean Verifikasi
            'mitraPending' => Mitra::where('status_mitra', 'pending')
                ->with(['user', 'kategori', 'lokasi'])
                ->latest()
                ->get(),

            // 4. Data Terverifikasi
            'mitraVerified' => Mitra::where('status_mitra', 'verified')
                ->with(['user', 'kategori', 'lokasi'])
                ->latest()
                ->take(5)
                ->get()
        ]);
    }

    // FUNGSI BARU UNTUK UPDATE STATUS
    public function updateStatus(Request $request, Mitra $mitra)
    {
        // Validasi input
        $request->validate([
            'status' => 'required|in:verified,rejected'
        ]);

        // Update database
        $mitra->update([
            'status_mitra' => $request->status
        ]);

        // Refresh data di Vue otomatis
        return redirect()->back();
    }
}