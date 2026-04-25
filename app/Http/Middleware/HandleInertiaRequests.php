<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => [
                // KUNCI UTAMA: load('mitra') memastikan data logo & perusahaan ikut terkirim
                'user' => $request->user() ? $request->user()->load('mitra') : null,
                'role' => $request->user() ? $request->user()->role : null,
            ],

            // Flash messages untuk notifikasi SweetAlert/Toast di frontend
            'flash' => [
                'success' => fn() => $request->session()->get('success'),
                'error' => fn() => $request->session()->get('error'),
                'status' => fn() => $request->session()->get('status'),
            ],

            // Ziggy route (opsional tapi sangat membantu agar route() jalan di Vue)
            'ziggy' => fn() => [
                ...(new \Tighten\Ziggy\Ziggy)->toArray(),
                'location' => $request->url(),
            ],
        ]);
    }
}