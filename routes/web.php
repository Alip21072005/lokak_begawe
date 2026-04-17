<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

// Import Controller
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\Admin\KelolapelamarController;
use App\Http\Controllers\Admin\KelolalowonganController;
use App\Http\Controllers\Admin\KelolamitraController;
use App\Http\Controllers\Admin\PesanadminController;
use App\Http\Controllers\Auth\RegisterPelamarController;
use App\Http\Controllers\Auth\RegisterMitraController;
use App\Http\Controllers\Mitra\KelolapelamarkerjaController;
use App\Http\Controllers\Mitra\PasanglowonganController;
use App\Http\Controllers\Mitra\PesanmitraController;
use App\Http\Controllers\Pelamar\LamaranController;
use App\Http\Controllers\Pelamar\PesanController;
use App\Http\Controllers\Pelamar\LowongankerjaController;

// Import Controller Google Auth yang baru ditambahkan
use App\Http\Controllers\Auth\GoogleController;

// ==========================================
// 1. PUBLIC ROUTES
// ==========================================
Route::inertia('/', 'Welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('welcome');

// Rute Halaman List Lowongan
Route::inertia('/lowongan', 'Lowongan')->name('lowongan');

Route::inertia('/mitra', 'Mitra')->name('mitra');

Route::inertia('/detail/detaillowongan', 'Detail/Detaillowongan')->name('detail.detaillowongan');


// ==========================================
// 2. GUEST ROUTES (Hanya untuk yang BELUM Login)
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/register/pelamar', fn() => inertia('auth/RegisterPelamar'))->name('register.pelamar');
    Route::get('/register/mitra', fn() => inertia('auth/RegisterMitra'))->name('register.mitra');
    Route::post('/register/pelamar', [RegisterPelamarController::class, 'store'])->name('register.pelamar.post');
    Route::post('/register/mitra', [RegisterMitraController::class, 'store'])->name('register.mitra.post');

    // --- GOOGLE AUTH ROUTES ---
    Route::get('/auth/google/redirect', [GoogleController::class, 'redirect'])->name('google.redirect');
    Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');
});

// ==========================================
// 3. AUTH ROUTES (Hanya untuk yang SUDAH Login)
// ==========================================
Route::middleware(['auth'])->group(function () {

    // --- DASHBOARD REDIRECTOR ---
    Route::get('/dashboard', function () {
        $user = Auth::user();
        $role = $user->role instanceof \UnitEnum ? $user->role->value : $user->role;

        return match ($role) {
            'admin' => redirect()->route('dashboard.admin'),
            'mitra' => redirect()->route('dashboard.mitra'),
            'pelamar' => redirect()->route('dashboard.pelamar'),
            default => redirect('/'),
        };
    })->name('dashboard');

    // --- ADMIN ---
    Route::get('/dashboard/admin', [DashboardAdminController::class, 'index'])->name('dashboard.admin');
    Route::get('/dashboard/admin/kelolapelamar', [KelolapelamarController::class, 'index'])->name('admin.kelolapelamar');
    Route::get('/dashboard/admin/kelolalowongan', [KelolalowonganController::class, 'index'])->name('admin.kelolalowongan');
    Route::get('/dashboard/admin/kelolamitra', [KelolamitraController::class, 'index'])->name('admin.kelolamitra');
    Route::get('/dashboard/admin/pesanadmin', [PesanadminController::class, 'index'])->name('admin.pesanadmin');

    // --- MITRA ---
    Route::get('/dashboard/mitra', function () {
        $user = User::with('mitra')->find(Auth::id());
        return inertia('Mitra/Dashboard', ['auth' => ['user' => $user]]);
    })->name('dashboard.mitra');
    Route::get('/dashboard/mitra/kelolapelamarkerja', [KelolapelamarkerjaController::class, 'index'])->name('mitra.kelolapelamarkerja');
    Route::get('/dashboard/mitra/pasanglowongan', [PasanglowonganController::class, 'index'])->name('mitra.pasanglowongan');
    Route::get('/dashboard/mitra/pesanmitra', [PesanmitraController::class, 'index'])->name('mitra.pesanmitra');

    // --- PELAMAR ---
    Route::get('/dashboard/pelamar', function () {
        $user = User::with('pelamar')->find(Auth::id());
        return inertia('Pelamar/Dashboard', ['auth' => ['user' => $user]]);
    })->name('dashboard.pelamar');
    Route::get('/dashboard/pelamar/lamaran', [LamaranController::class, 'index'])->name('pelamar.lamaran');
    Route::get('/dashboard/pelamar/pesan', [PesanController::class, 'index'])->name('pelamar.pesan');
    Route::get('/dashboard/pelamar/lowongankerja', [LowongankerjaController::class, 'index'])->name('pelamar.lowongankerja');


});

require __DIR__ . '/settings.php';
