<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

// --- Controllers Publik ---
use App\Http\Controllers\LandingController;
use App\Http\Controllers\LowonganController;
use App\Http\Controllers\MitraController;

// --- Controllers Admin ---
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\Admin\KelolapelamarController;
use App\Http\Controllers\Admin\KelolalowonganController;
use App\Http\Controllers\Admin\KelolamitraController;
use App\Http\Controllers\Admin\PesanadminController;

// --- Controllers Auth & Google ---
use App\Http\Controllers\Auth\RegisterPelamarController;
use App\Http\Controllers\Auth\RegisterMitraController;
use App\Http\Controllers\Auth\GoogleController;

// --- Controllers Mitra ---
use App\Http\Controllers\Mitra\DashboardMitraController;
use App\Http\Controllers\Mitra\KelolapelamarkerjaController;
use App\Http\Controllers\Mitra\PasanglowonganController;
use App\Http\Controllers\Mitra\PesanmitraController;

// --- Controllers Pelamar ---
use App\Http\Controllers\Pelamar\DashboardPelamarController;
use App\Http\Controllers\Pelamar\LamaranController;
use App\Http\Controllers\Pelamar\PesanController;
use App\Http\Controllers\Pelamar\LowongankerjaController;

// --- Controller Settings ---
use App\Http\Controllers\Settings\ProfileController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', [LandingController::class, 'index'])->name('welcome');

// Menggunakan Controller agar data database (Seeder) muncul di halaman publik
Route::get('/lowongan', [LowonganController::class, 'index'])->name('lowongan.index');
Route::get('/mitra', [MitraController::class, 'index'])->name('mitra.index');


Route::get('/lowongan/{id}', [LowonganController::class, 'show'])->name('lowongan.show');
Route::get('/mitra/{id}', [MitraController::class, 'show'])->name('mitra.show');


/*
|--------------------------------------------------------------------------
| GUEST ROUTES (Hanya untuk yang BELUM Login)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/register/pelamar', fn() => inertia('auth/RegisterPelamar'))->name('register.pelamar');
    Route::get('/register/mitra', [RegisterMitraController::class, 'create'])->name('register.mitra');
    Route::post('/register/pelamar', [RegisterPelamarController::class, 'store'])->name('register.pelamar.post');
    Route::post('/register/mitra', [RegisterMitraController::class, 'store'])->name('register.mitra.post');

    // Google Auth
    Route::get('/auth/google/redirect', [GoogleController::class, 'redirect'])->name('google.redirect');
    Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');
});


/*
|--------------------------------------------------------------------------
| AUTH ROUTES (Wajib Login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // --- DASHBOARD REDIRECTOR ---
    Route::get('/dashboard', function () {
        $user = Auth::user();
        $role = $user->role instanceof \UnitEnum ? $user->role->value : $user->role;

        return match ($role) {
            'admin'   => redirect()->route('admin.dashboard'),
            'mitra'   => redirect()->route('mitra.dashboard'),
            'pelamar' => redirect()->route('pelamar.dashboard'),
            default   => redirect('/'),
        };
    })->name('dashboard');

    // --- ADMIN ROUTES ---
    Route::prefix('dashboard/admin')->group(function () {
        Route::get('/', [DashboardAdminController::class, 'index'])->name('admin.dashboard');
        Route::get('/kelolapelamar', [KelolapelamarController::class, 'index'])->name('admin.kelolapelamar');
        Route::get('/kelolalowongan', [KelolalowonganController::class, 'index'])->name('admin.kelolalowongan');
        Route::get('/kelolamitra', [KelolamitraController::class, 'index'])->name('admin.kelolamitra');
        Route::get('/pesanadmin', [PesanadminController::class, 'index'])->name('admin.pesanadmin');

        Route::patch('/mitra/{mitra}/status', [DashboardAdminController::class, 'updateStatus'])->name('admin.mitra.update-status');
        Route::patch('/kelolalowongan/{id}/status', [KelolalowonganController::class, 'updateStatus'])->name('admin.lowongan.update-status');
        Route::delete('/hapus-akun-user/{id}', [DashboardAdminController::class, 'deletePelamar'])->name('admin.user.delete');
        Route::patch('/blokir-akun-user/{id}', [DashboardAdminController::class, 'blockPelamar'])->name('admin.user.block');
        Route::delete('/kelolalowongan/{id}', [KelolalowonganController::class, 'destroy'])->name('admin.lowongan.delete');
    });

    // --- MITRA ROUTES ---
    Route::prefix('dashboard/mitra')->group(function () {
        Route::get('/', [DashboardMitraController::class, 'index'])->name('mitra.dashboard');

        Route::middleware(['verified_mitra'])->group(function () {
            Route::get('/kelolapelamarkerja', [KelolapelamarkerjaController::class, 'index'])->name('mitra.kelolapelamarkerja');
            Route::patch('/pelamar/{id}/status', [KelolapelamarkerjaController::class, 'updateStatus'])->name('mitra.pelamar.status');

            Route::get('/pasanglowongan', [PasanglowonganController::class, 'index'])->name('mitra.pasanglowongan');
            Route::post('/pasanglowongan', [PasanglowonganController::class, 'store'])->name('mitra.pasanglowongan.store');
            Route::put('/pasanglowongan/{id}', [PasanglowonganController::class, 'update'])->name('mitra.pasanglowongan.update');
            Route::delete('/pasanglowongan/{id}', [PasanglowonganController::class, 'destroy'])->name('mitra.pasanglowongan.destroy');

            Route::get('/pesanmitra', [PesanmitraController::class, 'index'])->name('mitra.pesanmitra');
        });
    });

    // --- PELAMAR ROUTES ---
    Route::prefix('dashboard/pelamar')->group(function () {
        Route::get('/', [DashboardPelamarController::class, 'index'])->name('pelamar.dashboard');
        Route::get('/lamaran', [LamaranController::class, 'index'])->name('pelamar.lamaran');
        Route::get('/pesan', [PesanController::class, 'index'])->name('pelamar.pesan');
        Route::get('/lowongankerja', [LowongankerjaController::class, 'index'])->name('pelamar.lowongankerja');
    });
});

/*
|--------------------------------------------------------------------------
| CORE SYSTEM & EXTERNAL ROUTES
|--------------------------------------------------------------------------
*/
if (file_exists(__DIR__ . '/settings.php')) {
    require __DIR__ . '/settings.php';
}

if (file_exists(__DIR__ . '/auth.php')) {
    require __DIR__ . '/auth.php';
}