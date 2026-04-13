<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\User;      // Pastikan Model ini ada
use App\Models\Mitra;   // Sesuaikan nama model Mitra Anda
use App\Models\Lowongan;       // Sesuaikan nama model Lowongan Anda
use Illuminate\Support\Facades\Auth;

class DashboardAdminController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Proteksi tambahan jika user bukan admin
        $role = $user->role instanceof \UnitEnum ? $user->role->value : $user->role;
        if ($role !== 'admin') {
            return redirect()->route('dashboard');
        }

        return Inertia::render('Admin/Dashboard', [
            'auth' => [
                'user' => $user
            ],
            'counts' => [
                'pelamar' => User::where('role', 'pelamar')->count(),
                'mitra'   => Mitra::count(),
                'lowongan' => Lowongan::count(),
            ]
        ]);
    }
}