<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Mitra;
use App\Models\Lokasi;
use App\Models\Kategori;
use App\Enums\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class RegisterMitraController extends Controller
{
    // FUNGSI INI YANG SEMPAT HILANG
    public function create()
    {
        $lokasis = Lokasi::orderBy('nama_lokasi', 'asc')->get();
        $kategoris = Kategori::orderBy('nama_kategori', 'asc')->get();

        return Inertia::render('auth/RegisterMitra', [
            'lokasis' => $lokasis,
            'kategoris' => $kategoris
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
            'company_name' => 'required|string|max:255',
            'nohp_mitra' => 'required|string|max:20',
            'lokasi_id' => 'required|exists:lokasis,id',
            'kategori_id' => 'required|exists:kategoris,id',
            'alamat_mitra' => 'required|string',
            'deksipsi_mitra' => 'required|string', // Pastikan baris ini ada
            'logo_mitra' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'banner_mitra' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        DB::beginTransaction();

        try {
            $logoPath = null;
            if ($request->hasFile('logo_mitra')) {
                $logoPath = $request->file('logo_mitra')->store('mitra/logo', 'public');
            }

            $bannerPath = null;
            if ($request->hasFile('banner_mitra')) {
                $bannerPath = $request->file('banner_mitra')->store('mitra/banner', 'public');
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => UserRole::MITRA,
            ]);

            Mitra::create([
                'user_id' => $user->id,
                'nama_mitra' => $request->company_name,
                'email_mitra' => $request->email,
                'deksipsi_mitra' => $request->deksipsi_mitra, // Fix typo di sini
                'alamat_mitra' => $request->alamat_mitra,
                'nohp_mitra' => $request->nohp_mitra,
                'lokasi_id' => $request->lokasi_id,
                'kategori_id' => $request->kategori_id,
                'logo_mitra' => $logoPath,
                'banner_mitra' => $bannerPath,
            ]);

            DB::commit();

            Auth::login($user->fresh());

            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Registrasi gagal, silakan coba lagi.']);
        }
    }
}