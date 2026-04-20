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
            'nama_mitra' => 'required|string|max:255', // Sudah disesuaikan
            'nohp_mitra' => 'required|string|max:20',
            'lokasi_id' => 'required|exists:lokasis,id',
            'kategori_id' => 'required|exists:kategoris,id',
            'alamat_mitra' => 'required|string',
            'deskripsi_mitra' => 'required|string', // Sudah disesuaikan
            'logo_mitra' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'banner_mitra' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ], [
            'name.required' => 'Nama lengkap penanggung jawab wajib diisi.',
            'email.required' => 'Email akun tidak boleh kosong.',
            'email.unique' => 'Email ini sudah terdaftar, silakan gunakan email lain.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal harus 8 karakter.',
            'nama_mitra.required' => 'Nama perusahaan wajib diisi.', // Sudah disesuaikan
            'nohp_mitra.required' => 'Nomor telepon perusahaan wajib diisi.',
            'lokasi_id.required' => 'Silakan pilih lokasi perusahaan Anda.',
            'kategori_id.required' => 'Silakan pilih bidang industri perusahaan.',
            'alamat_mitra.required' => 'Alamat lengkap wajib diisi.',
            'deskripsi_mitra.required' => 'Deskripsi perusahaan jangan dikosongkan.', // Sudah disesuaikan
            'logo_mitra.image' => 'File logo harus berupa gambar.',
            'banner_mitra.max' => 'Ukuran banner maksimal adalah 5MB.',
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
                'nama_mitra' => $request->nama_mitra, // Mengambil data yang benar
                'email_mitra' => $request->email,
                'deskripsi_mitra' => $request->deskripsi_mitra, // INI BIANG KEROKNYA, sekarang sudah benar!
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
            // Lemparkan error aslinya agar ketahuan jika masih ada yang salah
            throw $e;
        }
    }
}