<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Lokasi;
use App\Models\Kategori; // Import model Kategori
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // --- SEEDER USER ---
        User::updateOrCreate(
            ['email' => 'muhamadalipmaulana3@gmail.com'],
            [
                'name' => 'Alip maulana',
                'email' => 'muhamadalipmaulana3@gmail.com',
                'password' => Hash::make('Alip210725_'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@mail.com'],
            [
                'name' => 'Admin',
                'email' => 'admin@mail.com',
                'password' => Hash::make('admin'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'mitra@mail.com'],
            [
                'name' => 'Mitra',
                'email' => 'mitra@mail.com',
                'password' => Hash::make('mitra'),
                'role' => 'mitra',
            ]
        );

        User::updateOrCreate(
            ['email' => 'pelamar@mail.com'],
            [
                'name' => 'Pelamar',
                'email' => 'pelamar@mail.com',
                'password' => Hash::make('pelamar'),
                'role' => 'pelamar',
            ]
        );

        // --- SEEDER LOKASI BENGKULU ---
        $this->seedLokasiBengkulu();

        // --- SEEDER KATEGORI INDUSTRI ---
        $this->seedKategoriIndustri();
    }

    /**
     * Fungsi helper untuk memasukkan data lokasi
     */
    private function seedLokasiBengkulu(): void
    {
        $daftarLokasi = [
            'Kota Bengkulu',
            'Kabupaten Bengkulu Selatan',
            'Kabupaten Bengkulu Tengah',
            'Kabupaten Bengkulu Utara',
            'Kabupaten Kaur',
            'Kabupaten Kepahiang',
            'Kabupaten Lebong',
            'Kabupaten Mukomuko',
            'Kabupaten Rejang Lebong',
            'Kabupaten Seluma',
        ];

        foreach ($daftarLokasi as $lokasi) {
            Lokasi::updateOrCreate(
                ['nama_lokasi' => $lokasi],
                ['nama_lokasi' => $lokasi]
            );
        }
    }

    /**
     * Fungsi helper untuk memasukkan data kategori industri
     */
    private function seedKategoriIndustri(): void
    {
        $daftarKategori = [
            'Teknologi Informasi & Komputer',
            'Pendidikan & Pelatihan',
            'Kesehatan & Medis',
            'Perbankan & Keuangan',
            'Perdagangan & Retail',
            'Manufaktur & Produksi',
            'Pariwisata & Perhotelan',
            'Konstruksi & Bangunan',
            'Pertanian & Perkebunan',
            'Otomotif & Transportasi',
            'Media & Hiburan',
            'Layanan Masyarakat & Pemerintahan',
        ];

        foreach ($daftarKategori as $kategori) {
            Kategori::updateOrCreate(
                ['nama_kategori' => $kategori],
                ['nama_kategori' => $kategori]
            );
        }
    }
}