<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureMitraIsVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        $mitra = $user->mitra;

        if ($user && $user->role->value === 'mitra') {
            if ($mitra && $mitra->status_mitra === 'verified') {
                return $next($request);
            }

            // Tentukan pesan berdasarkan status
            $message = 'Akses Ditolak. Akun Anda sedang dalam proses verifikasi oleh Admin.';
            if ($mitra && $mitra->status_mitra === 'rejected') {
                $message = 'Akses Ditolak. Maaf, pendaftaran mitra Anda ditolak. Silakan hubungi admin untuk informasi lebih lanjut.';
            }

            // Redirect ke dashboard dengan flash message 'error'
            return redirect()->route('mitra.dashboard')->with('error', $message);
        }

        return redirect('/');
    }
}