<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterMitraController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'mitra',
        ]);

        \App\Models\Mitra::create([
            'user_id' => $user->id,
            'nama_mitra' => $request->company_name,
            'email_mitra' => $request->email,
            'deksipsi_mitra' => '-',
            'alamat_mitra' => '-',
            'nohp_mitra' => '-',
            'lokasi_id' => '...',
            'kategori_id' => '...',
        ]);

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}