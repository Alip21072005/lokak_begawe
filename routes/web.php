<?php

use App\Http\Controllers\Admin\KelolalowonganController;
use App\Http\Controllers\Admin\KelolamitraController;
use App\Http\Controllers\Admin\KelolapelamarController;
use App\Http\Controllers\Admin\PesanadminController;
use App\Http\Controllers\Auth\RegisterPelamarController;
use App\Http\Controllers\Auth\RegisterMitraController;
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\Mitra\KelolapelamarkerjaController;
use App\Http\Controllers\Mitra\PasanglowonganController;
use App\Http\Controllers\Mitra\PesanmitraController;
use App\Http\Controllers\Pelamar\LamaranController;
use App\Http\Controllers\Pelamar\PesanController;
use App\Http\Controllers\Pelamar\TawarankerjaController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Features;

// ==========================================
// 1. PUBLIC ROUTES (Bisa Diakses Guest & Auth)
// ==========================================
Route::inertia('/', 'Welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('welcome');

// Dikeluarkan dari middleware 'auth' agar Guest bisa melihatnya
Route::inertia('/lowongan', 'Lowongan')->name('lowongan');

// Tambahan rute untuk halaman mitra publik (daftar perusahaan)
Route::inertia('/mitra', 'Mitra')->name('mitra');


// ==========================================
// 2. GUEST ROUTES (Hanya untuk yang BELUM Login)
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/register/pelamar', fn() => inertia('auth/RegisterPelamar'))->name('register.pelamar');
    Route::get('/register/mitra', fn() => inertia('auth/RegisterMitra'))->name('register.mitra');

    Route::post('/register/pelamar', [RegisterPelamarController::class, 'store'])->name('register.pelamar.post');
    Route::post('/register/mitra', [RegisterMitraController::class, 'store'])->name('register.mitra.post');
});


// ==========================================
// 3. AUTH ROUTES (Hanya untuk yang SUDAH Login)
// ==========================================
Route::middleware(['auth'])->group(function () {

    // --- DASHBOARD REDIRECTOR ---
    // Mengarahkan user ke dashboard masing-masing sesuai Role
    Route::get('/dashboard', function () {
        $user = Auth::user();
        $role = $user->role instanceof \UnitEnum ? $user->role->value : $user->role;

        return match ($role) {
            'admin'   => redirect()->route('dashboard.admin'),
            'mitra'   => redirect()->route('dashboard.mitra'),
            'pelamar' => redirect()->route('dashboard.pelamar'),
            default   => (function () {
                Auth::logout();
                return redirect('/')->with('error', 'Role tidak valid.');
            })()
        };
    })->name('dashboard');

    // --- ADMIN ROUTES ---
    Route::get('/dashboard/admin', [DashboardAdminController::class, 'index'])
        ->name('dashboard.admin');

    Route::get('/dashboard/admin/kelolapelamar', [KelolapelamarController::class, 'index'])
        ->name('admin.kelolapelamar');

    Route::get('/dashboard/admin/kelolalowongan', [KelolalowonganController::class, 'index'])
        ->name('admin.kelolalowongan');

    Route::get('/dashboard/admin/kelolamitra', [KelolamitraController::class, 'index'])
        ->name('admin.kelolamitra');

    Route::get('/dashboard/admin/pesanadmin', [PesanadminController::class, 'index'])
        ->name('admin.pesanadmin');

    // --- MITRA ROUTES ---
    Route::get('/dashboard/mitra', function () {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $role = $user->role instanceof \UnitEnum ? $user->role->value : $user->role;

        if ($role !== 'mitra') return redirect()->route('dashboard');

        return inertia('Mitra/Dashboard', [
            'auth' => ['user' => $user->load('mitra')]
        ]);
    })->name('dashboard.mitra');

    Route::get('/dashboard/mitra/kelolapelamarkerja', [KelolapelamarkerjaController::class, 'index'])
        ->name('mitra.kelolapelamarkerja');

    Route::get('/dashboard/mitra/pasanglowongan', [PasanglowonganController::class, 'index'])
        ->name('mitra.pasanglowongan');

    Route::get('/dashboard/mitra/pesanmitra', [PesanmitraController::class, 'index'])
        ->name('mitra.pesanmitra');

    // --- PELAMAR ROUTES ---
    Route::get('/dashboard/pelamar', function () {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $role = $user->role instanceof \UnitEnum ? $user->role->value : $user->role;

        if ($role !== 'pelamar') return redirect()->route('dashboard');

        return inertia('Pelamar/Dashboard', [
            'auth' => ['user' => $user->load('pelamar')]
        ]);
    })->name('dashboard.pelamar');

    Route::get('/dashboard/pelamar/lamaran', [LamaranController::class, 'index'])
        ->name('pelamar.lamaran');

    Route::get('/dashboard/pelamar/pesan', [PesanController::class, 'index'])
        ->name('pelamar.pesan');

    Route::get('/dashboard/pelamar/tawarankerja', [TawarankerjaController::class, 'index'])
        ->name('pelamar.tawarankerja');
});


// ==========================================
// 4. SETTINGS & DEBUG ROUTES
// ==========================================
require __DIR__ . '/settings.php';

Route::get('/cek-auth', function () {
    if (Auth::check()) {
        $user = Auth::user();
        return "Berhasil! Anda login sebagai: " . $user->email . " | Role di DB: " . ($user->role ?? 'KOSONG');
    } else {
        return "GAGAL: Anda saat ini terdeteksi BELUM LOGIN.";
    }
});