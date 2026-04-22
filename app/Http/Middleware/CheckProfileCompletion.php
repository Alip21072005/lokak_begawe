<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckProfileCompletion
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Hanya lakukan pengecekan jika user login sebagai 'pelamar'
        if ($user && $user->role->value === 'pelamar') {

            $pelamar = $user->pelamar; // Memanggil relasi pelamar di Model User


            $isComplete = $pelamar &&
                $pelamar->nohp_pelamar &&
                $pelamar->lokasi_id &&
                $pelamar->cv_pelamar;

            if (!$isComplete) {
                // Simpan pesan error ke session untuk ditampilkan di UI profil
                return redirect()->route('profile.edit')
                    ->with('error', 'Waduh! Kamu wajib melengkapi No. WhatsApp, Lokasi, dan Upload CV sebelum bisa melamar pekerjaan.');
            }
        }

        return $next($request);
    }
}