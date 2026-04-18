<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureMitraIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle($request, $next)
    {
        $user = Auth::user();
        if ($user && $user->role === 'mitra' && $user->mitra->status_mitra !== 'verified') {
            // Jika belum verified, paksa balik ke dashboard mitra dengan pesan error
            return redirect()->route('dashboard.mitra')->with('error', 'Akun belum diverifikasi admin.');
        }
        return $next($request);
    }
}