<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class PesanadminController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();;

        return Inertia::render('Admin/Pesanadmin', [
            'auth' => [
                'user' => $user
            ],

        ]);
    }
}