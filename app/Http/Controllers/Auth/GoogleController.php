<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {

            /** @var \Laravel\Socialite\Two\AbstractProvider $driver */
            $driver = Socialite::driver('google');

            // Trik bypass verifikasi SSL khusus untuk Localhost
            $googleUser = $driver
                ->setHttpClient(new \GuzzleHttp\Client(['verify' => false]))
                ->user();

            // Cek apakah user sudah ada di database berdasarkan email
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // Jika user sudah terdaftar (Bisa Pelamar, Mitra, atau Admin)
                // Kita update google_id-nya jika masih kosong
                if (!$user->google_id) {
                    $user->update(['google_id' => $googleUser->getId()]);
                }

                // Login user yang sudah ada
                Auth::login($user);
            } else {
                // Jika ini adalah user BARU yang langsung klik Google Login
                // UUID otomatis terbuat berkat App\Traits\UUID di model Anda
                $newUser = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'password' => null, // Boleh null karena sudah kita ubah di migrasi
                    'role' => 'pelamar', // Default role untuk pendaftar baru via Google
                    'google_id' => $googleUser->getId(),
                ]);

                Auth::login($newUser);
            }

            // Arahkan ke endpoint redirector yang akan mengecek role dan masuk ke dashboard masing-masing
            return redirect()->route('dashboard');
        } catch (\Exception $e) {

            return redirect('/login')->with('error', 'Gagal login menggunakan Google. Silakan coba lagi.');
        }
    }
}