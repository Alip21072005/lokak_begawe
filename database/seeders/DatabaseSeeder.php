<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Lokasi;
use App\Models\Kategori;
use App\Models\Mitra;
use App\Models\Pelamar;
use App\Models\Lowongan;
use App\Models\Lamaran;
use App\Models\Rating;
use App\Models\MasterSkill;
use App\Models\MasterInstansi;
use App\Models\MasterPerusahaan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // 1. MASTER DATA
        $this->command->info('Menyiapkan Pondasi Master Data...');
        $this->seedLokasiBengkulu();
        $this->seedKategoriIndustri();
        $this->seedMasterSkills();
        $this->seedMasterInstansi();
        $this->seedMasterPerusahaan();

        $lokasiIds = Lokasi::pluck('id')->toArray();
        $skillIds = MasterSkill::pluck('id')->toArray();
        $instansiIds = MasterInstansi::pluck('id')->toArray();
        $mPerusahaanIds = MasterPerusahaan::pluck('id')->toArray();

        // 2. AKUN UTAMA
        $this->command->info('Membuat Akun Utama...');
        User::updateOrCreate(['email' => 'muhamadalipmaulana3@gmail.com'], [
            'name' => 'Alip Maulana (Admin)',
            'password' => Hash::make('Alip210725_'),
            'role' => 'admin',
        ]);

        $userDeCode = User::updateOrCreate(['email' => 'decode@gmail.com'], [
            'name' => 'De Code Community',
            'password' => Hash::make('Decode123'),
            'role' => 'mitra',
        ]);

        $mitraDeCode = Mitra::updateOrCreate(['user_id' => $userDeCode->id], [
            'lokasi_id' => Lokasi::where('nama_lokasi', 'Kota Bengkulu')->first()->id,
            'kategori_id' => Kategori::where('nama_kategori', 'Teknologi Informasi & Software')->first()->id,
            'status_mitra' => 'verified',
            'nama_mitra' => 'De Code Community',
            'email_mitra' => 'decode@gmail.com',
            'website_mitra' => 'https://decode.id',
            'tahun_berdiri' => '2024',
            'skala_perusahaan' => '1 - 50 Karyawan',
            'deskripsi_mitra' => 'Hub teknologi lokal di Bengkulu.',
            'alamat_mitra' => 'Unib Belakang, Bengkulu',
            'nohp_mitra' => '089512345678',
        ]);

        // 3. GENERATE MITRA & LOWONGAN (DENGAN SKILL)
        $this->command->info('Membuat Mitra & Lowongan dengan Syarat...');
        $perusahaanData = $this->getDataMitraRealistik();
        $lowonganIds = [];

        foreach ($perusahaanData as $data) {
            $kategori = Kategori::where('nama_kategori', $data['kategori'])->first();
            if (!$kategori) continue;

            $uMitra = User::create([
                'name' => $data['nama'],
                'email' => strtolower(str_replace([' ', '.'], '', $data['nama'])) . '@hrd.com',
                'password' => Hash::make('password'),
                'role' => 'mitra',
            ]);

            $mitra = Mitra::create([
                'user_id' => $uMitra->id,
                'lokasi_id' => $faker->randomElement($lokasiIds),
                'kategori_id' => $kategori->id,
                'status_mitra' => 'verified',
                'nama_mitra' => $data['nama'],
                'email_mitra' => $uMitra->email,
                'website_mitra' => 'https://www.example.co.id',
                'tahun_berdiri' => $faker->numberBetween(1990, 2010),
                'skala_perusahaan' => '500+ Karyawan',
                'deskripsi_mitra' => $data['deskripsi'],
                'alamat_mitra' => $faker->address,
                'nohp_mitra' => $faker->phoneNumber,
            ]);

            foreach ($data['lowongan'] as $judul) {
                $lowongan = Lowongan::create([
                    'mitra_id' => $mitra->id,
                    'lokasi_id' => $mitra->lokasi_id,
                    'judul_lowongan' => $judul,
                    'deskripsi_lowongan' => $faker->paragraph(3),
                    'tipe_pekerjaan' => $faker->randomElement(['Full-time', 'Contract', 'Internship']),
                    'gaji_min' => $faker->numberBetween(30, 50) * 100000,
                    'gaji_max' => $faker->numberBetween(60, 100) * 100000,
                    'tanggal_expired' => $faker->dateTimeBetween('now', '+2 months'),
                    'status_lowongan' => 'verified',
                    // TAMBAHAN FIELD BARU
                    'minimal_pendidikan' => $faker->randomElement(['SMA/SMK', 'D3', 'S1']),
                    'minimal_pengalaman' => $faker->numberBetween(0, 3),
                ]);

                // Tambahkan 2-3 Skill yang dibutuhkan ke Lowongan (Tabel Pivot)
                $reqSkills = (array) array_rand(array_flip($skillIds), rand(2, 3));
                foreach ($reqSkills as $sId) {
                    DB::table('lowongan_skills')->insert([
                        'id' => Str::uuid(),
                        'lowongan_id' => $lowongan->id,
                        'master_skill_id' => $sId,
                        'created_at' => now(),
                    ]);
                }

                $lowonganIds[] = $lowongan->id;
            }
        }

        // 4. GENERATE 50 PELAMAR (LENGKAP)
        $this->command->info('Membuat 50 Pelamar dengan Profil Lengkap...');
        for ($i = 0; $i < 50; $i++) {
            $user = User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password'),
                'role' => 'pelamar',
            ]);

            $pelamar = Pelamar::create([
                'user_id' => $user->id,
                'lokasi_id' => $faker->randomElement($lokasiIds),
                'nama_pelamar' => $user->name,
                'email_pelamar' => $user->email,
                'nohp_pelamar' => $faker->phoneNumber,
                'alamat_pelamar' => $faker->address,
                'jenis_kelamin' => $faker->randomElement(['L', 'P']),
                'bio' => $faker->sentence(10),
            ]);

            // Tambahkan Skills Pelamar
            $randomSkills = (array) array_rand(array_flip($skillIds), rand(3, 5));
            foreach ($randomSkills as $sId) {
                DB::table('skills')->insert([
                    'id' => Str::uuid(),
                    'pelamar_id' => $pelamar->pelamar_id,
                    'master_skill_id' => $sId,
                    'created_at' => now(),
                ]);
            }

            // Tambahkan Pendidikan Pelamar
            DB::table('pendidikans')->insert([
                'id' => Str::uuid(),
                'pelamar_id' => $pelamar->pelamar_id,
                'master_instansi_id' => $faker->randomElement($instansiIds),
                'gelar' => $faker->randomElement(['S1 Sistem Informasi', 'S1 Teknik Informatika', 'SMA IPA', 'SMK Otomotif']),
                'tgl_mulai' => '2019-08-01',
                'tgl_lulus' => '2023-08-01',
            ]);

            // Tambahkan Pengalaman Pelamar
            DB::table('pengalamans')->insert([
                'id' => Str::uuid(),
                'pelamar_id' => $pelamar->pelamar_id,
                'master_perusahaan_id' => $faker->randomElement($mPerusahaanIds),
                'posisi' => $faker->jobTitle,
                'tgl_mulai' => '2024-01-01',
                'is_current' => true,
                'deskripsi' => $faker->sentence,
            ]);
        }

        // 5. LAMARAN & RATING
        $this->command->info('Mengirim Lamaran & Rating...');
        $allPelamarIds = Pelamar::pluck('pelamar_id')->toArray();
        $allMitraIds = Mitra::pluck('id')->toArray();
        $allPelamarUserIds = User::where('role', 'pelamar')->pluck('id')->toArray();

        for ($i = 0; $i < 100; $i++) {
            Lamaran::create([
                'pelamar_id' => $faker->randomElement($allPelamarIds),
                'lowongan_id' => $faker->randomElement($lowonganIds),
                'status' => $faker->randomElement(['pending', 'accepted', 'rejected', 'interview'])
            ]);
        }

        $this->command->info('Selesai!');
    }

    // --- SEEDER HELPERS (Tetap sama seperti punyamu) ---
    private function seedLokasiBengkulu()
    {
        $data = ['Kota Bengkulu', 'Bengkulu Selatan', 'Bengkulu Tengah', 'Bengkulu Utara', 'Kaur', 'Kepahiang', 'Lebong', 'Mukomuko', 'Rejang Lebong', 'Seluma'];
        foreach ($data as $l) Lokasi::updateOrCreate(['nama_lokasi' => $l]);
    }

    private function seedKategoriIndustri()
    {
        $data = ['Teknologi Informasi & Software', 'Perbankan & Keuangan', 'Kesehatan & Medis', 'Pendidikan & Pengajaran', 'Perdagangan Besar & Retail', 'Konstruksi & Real Estate'];
        foreach ($data as $k) Kategori::updateOrCreate(['nama_kategori' => $k]);
    }

    private function seedMasterSkills()
    {
        $skills = ['Laravel', 'Vue.js', 'React.js', 'PHP', 'JavaScript', 'Python', 'Accounting', 'Graphic Design', 'Public Speaking', 'Microsoft Excel'];
        foreach ($skills as $s) MasterSkill::firstOrCreate(['nama_skill' => $s]);
    }

    private function seedMasterInstansi()
    {
        $instansis = [['nama' => 'Universitas Bengkulu', 'jenis' => 'Universitas'], ['nama' => 'Universitas Dehasen Bengkulu', 'jenis' => 'Universitas'], ['nama' => 'SMK Negeri 1 Kota Bengkulu', 'jenis' => 'SMK']];
        foreach ($instansis as $i) MasterInstansi::firstOrCreate(['nama_instansi' => $i['nama']], ['jenis_instansi' => $i['jenis']]);
    }

    private function seedMasterPerusahaan()
    {
        $perusahaans = [['nama' => 'Bank Bengkulu', 'bidang' => 'Perbankan'], ['nama' => 'PT Telkom Indonesia', 'bidang' => 'Telekomunikasi'], ['nama' => 'PT Aranus', 'bidang' => 'Teknologi']];
        foreach ($perusahaans as $p) MasterPerusahaan::firstOrCreate(['nama_perusahaan' => $p['nama']], ['bidang_usaha' => $p['bidang']]);
    }

    private function getDataMitraRealistik(): array
    {
        return [
            ['nama' => 'Bank Bengkulu', 'kategori' => 'Perbankan & Keuangan', 'deskripsi' => 'Bank Pembangunan Daerah Bengkulu.', 'lowongan' => ['Teller', 'Customer Service']],
            ['nama' => 'PT Telkom Indonesia', 'kategori' => 'Teknologi Informasi & Software', 'deskripsi' => 'BUMN Telekomunikasi.', 'lowongan' => ['IT Support', 'Sales Executive']],
            ['nama' => 'RSUD dr. M. Yunus', 'kategori' => 'Kesehatan & Medis', 'deskripsi' => 'Rumah sakit rujukan.', 'lowongan' => ['Perawat', 'Apoteker']],
        ];
    }
}
