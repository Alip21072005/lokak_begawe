<?php

namespace App\Http\Controllers\Pelamar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class TawarankerjaController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();



        return Inertia::render('Pelamar/Tawarankerja', [
            'auth' => [
                'user' => $user->load('pelamar')
            ],
            'tawaranKerja' => []
        ]);
    }
}