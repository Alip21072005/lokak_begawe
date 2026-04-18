<?php

use App\Models\User;
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
use App\Http\Controllers\Auth\GoogleController;

// ==========================================
// 1. PUBLIC ROUTES
// ==========================================
Route::inertia('/', 'Welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('welcome');

Route::inertia('/lowongan', 'Lowongan')->name('lowongan');
Route::inertia('/mitra', 'Mitra')->name('mitra');

// ==========================================
// 2. GUEST ROUTES
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/register/pelamar', fn() => inertia('auth/RegisterPelamar'))->name('register.pelamar');
    Route::get('/register/mitra', [RegisterMitraController::class, 'create'])->name('register.mitra');
    Route::post('/register/pelamar', [RegisterPelamarController::class, 'store'])->name('register.pelamar.post');
    Route::post('/register/mitra', [RegisterMitraController::class, 'store'])->name('register.mitra.post');

    Route::get('/auth/google/redirect', [GoogleController::class, 'redirect'])->name('google.redirect');
    Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');
});

// ==========================================
// 3. AUTH ROUTES
// ==========================================
Route::middleware(['auth'])->group(function () {

    // --- DASHBOARD REDIRECTOR ---
    Route::get('/dashboard', function () {
        $user = Auth::user();
        $role = $user->role instanceof \UnitEnum ? $user->role->value : $user->role;

        return match ($role) {
            'admin'   => redirect()->route('dashboard.admin'),
            'mitra'   => redirect()->route('dashboard.mitra'),
            'pelamar' => redirect()->route('dashboard.pelamar'),
            default   => redirect('/'),
        };
    })->name('dashboard');

    // --- ADMIN ROUTES ---
    Route::prefix('dashboard/admin')->group(function () {
        Route::get('/', [DashboardAdminController::class, 'index'])->name('dashboard.admin');
        Route::get('/kelolapelamar', [KelolapelamarController::class, 'index'])->name('admin.kelolapelamar');
        Route::get('/kelolalowongan', [KelolalowonganController::class, 'index'])->name('admin.kelolalowongan');
        Route::get('/kelolamitra', [KelolamitraController::class, 'index'])->name('admin.kelolamitra');
        Route::get('/pesanadmin', [PesanadminController::class, 'index'])->name('admin.pesanadmin');

        // ROUTE UNTUK UPDATE STATUS MITRA
        Route::patch('/mitra/{mitra}/status', [DashboardAdminController::class, 'updateStatus'])->name('admin.mitra.update-status');
    });

    // --- MITRA ROUTES ---
    Route::prefix('dashboard/mitra')->group(function () {
        Route::get('/', function () {
            $user = User::with('mitra')->find(Auth::id());
            return inertia('Mitra/Dashboard', ['auth' => ['user' => $user]]);
        })->name('dashboard.mitra');

        Route::middleware(['verified_mitra'])->group(function () {
            Route::get('/kelolapelamarkerja', [KelolapelamarkerjaController::class, 'index'])->name('mitra.kelolapelamarkerja');
            Route::get('/pasanglowongan', [PasanglowonganController::class, 'index'])->name('mitra.pasanglowongan');
            Route::get('/pesanmitra', [PesanmitraController::class, 'index'])->name('mitra.pesanmitra');
        });
    });

    // --- PELAMAR ROUTES ---
    Route::prefix('dashboard/pelamar')->group(function () {
        Route::get('/', function () {
            $user = User::with('pelamar')->find(Auth::id());
            $mitraVerified = \App\Models\Mitra::where('status_mitra', 'verified')->with('kategori', 'lokasi')->get();

            return inertia('Pelamar/Dashboard', [
                'auth' => ['user' => $user],
                'mitraVerified' => $mitraVerified
            ]);
        })->name('dashboard.pelamar');

        Route::get('/lamaran', [LamaranController::class, 'index'])->name('pelamar.lamaran');
        Route::get('/pesan', [PesanController::class, 'index'])->name('pelamar.pesan');
        Route::get('/lowongankerja', [LowongankerjaController::class, 'index'])->name('pelamar.lowongankerja');
    });
});

require __DIR__ . '/settings.php';