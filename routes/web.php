<?php

use App\Http\Controllers\Auth\RegisterPelamarController;
use App\Http\Controllers\Auth\RegisterMitraController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Features;
use App\Models\User;

Route::inertia('/', 'Welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('welcome');

Route::middleware(['auth'])->group(function () {


    Route::get('dashboard', function () {
        $user = Auth::user();
        $role = $user->role instanceof \UnitEnum ? $user->role->value : $user->role;

        if ($role === 'admin') {
            return redirect()->route('dashboard.admin');
        } elseif ($role === 'mitra') {
            return redirect()->route('dashboard.mitra');
        } elseif ($role === 'pelamar') {
            return redirect()->route('dashboard.pelamar');
        } else {
            Auth::logout();
            return redirect('/')->with('error', 'Role tidak valid.');
        }
    })->name('dashboard');

    Route::get('dashboard/admin', function () {
        $user = Auth::user();
        $role = $user->role instanceof \UnitEnum ? $user->role->value : $user->role;

        if ($role !== 'admin') return redirect()->route('dashboard');

        return inertia('Admin/Dashboard', [
            'auth' => ['user' => $user]
        ]);
    })->name('dashboard.admin');


    Route::get('dashboard/mitra', function () {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $role = $user->role instanceof \UnitEnum ? $user->role->value : $user->role;
        if ($role !== 'mitra') return redirect()->route('dashboard');

        return inertia('Mitra/Dashboard', [
            'auth' => ['user' => $user->load('mitra')]
        ]);
    })->name('dashboard.mitra');


    Route::get('dashboard/pelamar', function () {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $role = $user->role instanceof \UnitEnum ? $user->role->value : $user->role;
        if ($role !== 'pelamar') return redirect()->route('dashboard');

        return inertia('Pelamar/Dashboard', [
            'auth' => ['user' => $user->load('pelamar')]
        ]);
    })->name('dashboard.pelamar');

    Route::inertia('lowongan', 'Lowongan/Index')->name('lowongan');
});

Route::middleware('guest')->group(function () {
    // Pastikan folder 'auth' ini benar-benar ada di resources/js/pages/auth/
    Route::get('/register/pelamar', fn() => inertia('auth/RegisterPelamar'))->name('register.pelamar');
    Route::get('/register/mitra', fn() => inertia('auth/RegisterMitra'))->name('register.mitra');

    Route::post('/register/pelamar', [RegisterPelamarController::class, 'store'])->name('register.pelamar.post');
    Route::post('/register/mitra', [RegisterMitraController::class, 'store'])->name('register.mitra.post');
});

require __DIR__ . '/settings.php';
Route::get('/cek-auth', function () {
    if (Auth::check()) {
        $user = Auth::user();
        return "Berhasil! Anda login sebagai: " . $user->email . " | Role di DB: " . ($user->role ?? 'KOSONG');
    } else {
        return "GAGAL: Anda saat ini terdeteksi BELUM LOGIN.";
    }
});