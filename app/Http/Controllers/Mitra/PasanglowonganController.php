<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class PasanglowonganController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();



        return Inertia::render('Mitra/Pasanglowongan', [
            'auth' => [
                'user' => $user->load('mitra')
            ],
            'pasanglowongan' => []
        ]);
    }
}