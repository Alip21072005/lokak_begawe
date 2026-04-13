<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $role = Auth::user()->role;

        return match ($role) {
            'admin'   => redirect()->route('admin.dashboard'),
            'pelamar' => redirect()->route('pelamar.dashboard'),
            'mitra'   => redirect()->route('mitra.dashboard'),
            default   => redirect('/'),
        };
    }
}