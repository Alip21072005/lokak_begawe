<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mitra;
use App\Models\User;
use App\Models\Kategori;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class KelolamitraController extends Controller
{
    /**
     * Menampilkan daftar mitra dengan filter pencarian
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $mitraPending = Mitra::with('kategori')
            ->where('status_mitra', 'pending')
            ->when($search, fn($q) => $q->where('nama_mitra', 'like', "%{$search}%"))
            ->latest()->get();

        $mitraActive = Mitra::with('kategori')
            ->where('status_mitra', 'verified')
            ->when($search, fn($q) => $q->where('nama_mitra', 'like', "%{$search}%"))
            ->latest()->get();

        return Inertia::render('Admin/Kelolamitra', [
            'mitraPending' => $mitraPending,
            'mitraActive' => $mitraActive,
            'stats' => [
                'total' => Mitra::count(),
                'pending' => Mitra::where('status_mitra', 'pending')->count(),
            ],
            'filters' => ['search' => $search]
        ]);
    }

    /**
     * Menampilkan halaman detail verifikasi mitra
     */
    public function show(string $id)
    {
        $mitra = Mitra::with(['kategori', 'lokasi', 'user'])->findOrFail($id);

        return Inertia::render('Admin/DetailVerifikasiMitra', [
            'mitra' => $mitra
        ]);
    }

    /**
     * Menyetujui atau mengubah status mitra
     */
    public function updateStatus(Request $request, string $id)
    {
        $request->validate(['status' => 'required|in:verified,pending']);

        $mitra = Mitra::findOrFail($id);
        $mitra->update(['status_mitra' => $request->status]);

        return redirect()->route('admin.kelolamitra')->with('success', 'Status mitra berhasil diperbarui');
    }

    /**
     * Form tambah mitra manual oleh Admin
     */
    public function create()
    {
        return Inertia::render('Admin/TambahMitraManual', [
            'kategoris' => Kategori::all(),
            'lokasis' => Lokasi::all(),
        ]);
    }

    /**
     * Simpan mitra baru dari form manual (Mendukung File Upload)
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'nama_mitra' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'lokasi_id' => 'required|exists:lokasis,id',
            'alamat_mitra' => 'required|string',
            'nohp_mitra' => 'required|string',
            'deskripsi_mitra' => 'nullable|string',
            'logo_mitra' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'banner_mitra' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        return DB::transaction(function () use ($request) {
            // 1. Buat User
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'mitra',
            ]);

            // 2. Handle Upload File
            $logoPath = $request->file('logo_mitra')
                ? $request->file('logo_mitra')->store('mitra/logos', 'public')
                : null;

            $bannerPath = $request->file('banner_mitra')
                ? $request->file('banner_mitra')->store('mitra/banners', 'public')
                : null;

            // 3. Buat Profil Mitra
            Mitra::create([
                'user_id' => $user->id,
                'nama_mitra' => $request->nama_mitra,
                'email_mitra' => $request->email,
                'kategori_id' => $request->kategori_id,
                'lokasi_id' => $request->lokasi_id,
                'alamat_mitra' => $request->alamat_mitra,
                'nohp_mitra' => $request->nohp_mitra,
                'deskripsi_mitra' => $request->deskripsi_mitra,
                'logo_mitra' => $logoPath,
                'banner_mitra' => $bannerPath,
                'status_mitra' => 'verified', // Admin yang buat langsung verified
            ]);

            return redirect()->route('admin.kelolamitra')->with('success', 'Mitra berhasil ditambahkan');
        });
    }
}
