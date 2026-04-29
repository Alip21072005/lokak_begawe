<?php

namespace App\Http\Middleware;

use App\Models\Lowongan;
use App\Models\Message;
use App\Models\Lamaran;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        $user = $request->user();
        $role = null;

        if ($user) {
            $role = $user->role;
            if ($role instanceof \BackedEnum) {
                $role = $role->value;
            } elseif ($role instanceof \UnitEnum) {
                $role = $role->name;
            }
        }

        // --- DINAMIS BADGE: Menghitung notifikasi berdasarkan role ---
        $notifications = [
            'admin_loker_pending' => 0,
            'mitra_lamaran_masuk' => 0,
            'unread_messages' => 0,
        ];

        if ($user && $role) {
            // Admin: Loker butuh verifikasi
            if ($role === 'admin') {
                $notifications['admin_loker_pending'] = Lowongan::where('status_lowongan', 'pending')->count();
            }

            // Mitra: Lamaran masuk status pending
            if ($role === 'mitra' && $user->mitra) {
                $notifications['mitra_lamaran_masuk'] = Lamaran::whereHas('lowongan', function ($q) use ($user) {
                    $q->where('mitra_id', $user->mitra->id);
                })->where('status', 'pending')->count();
            }

            // Pesan: Belum dibaca (Pastikan tabel messages menggunakan kolom 'read')
            $notifications['unread_messages'] = Message::where('read', false)
                ->where('sender_id', '!=', $user->id)
                ->whereHas('conversation', function ($query) use ($user) {
                    $query->where('sender_id', $user->id)
                        ->orWhere('receiver_id', $user->id);
                })->count();
        }

        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $user ? $user->load('mitra') : null,
                'role' => $role,
            ],
            'notifications' => $notifications, // Kirim ke Vue
            'flash' => [
                'success' => fn() => $request->session()->get('success'),
                'error' => fn() => $request->session()->get('error'),
                'status' => fn() => $request->session()->get('status'),
            ],
            'ziggy' => fn() => [
                ...(new \Tighten\Ziggy\Ziggy)->toArray(),
                'location' => $request->url(),
            ],
        ]);
    }
}
