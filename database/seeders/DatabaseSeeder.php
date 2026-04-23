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
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // 1. SEEDER LOKASI & KATEGORI
        $this->seedLokasiBengkulu();
        $this->seedKategoriIndustri();

        $lokasiIds = Lokasi::pluck('id')->toArray();

        // 2. SEEDER AKUN UTAMA (ADMIN, MITRA TESTING, PELAMAR TESTING)
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

        Mitra::updateOrCreate(['user_id' => $userDeCode->id], [
            'lokasi_id' => Lokasi::where('nama_lokasi', 'Kota Bengkulu')->first()->id,
            'kategori_id' => Kategori::where('nama_kategori', 'Teknologi Informasi')->first()->id,
            'status_mitra' => 'verified',
            'nama_mitra' => 'De Code Community',
            'email_mitra' => 'decode@gmail.com',
            'website_mitra' => 'https://decode.id',
            'tahun_berdiri' => '2024',
            'skala_perusahaan' => '1 - 50 Karyawan',
            'deskripsi_mitra' => 'Komunitas teknologi di Bengkulu yang fokus pada pengembangan bakat IT lokal.',
            'alamat_mitra' => 'Unib Belakang, Bengkulu',
            'nohp_mitra' => '089512345678',
        ]);

        $userAsep = User::updateOrCreate(['email' => 'asepmujikno@gmail.com'], [
            'name' => 'Asep Mujikno',
            'password' => Hash::make('Asep12345'),
            'role' => 'pelamar',
        ]);

        Pelamar::updateOrCreate(['user_id' => $userAsep->id], [
            'lokasi_id' => $faker->randomElement($lokasiIds),
            'nama_pelamar' => 'Asep Mujikno',
            'email_pelamar' => 'asepmujikno@gmail.com',
            'nohp_pelamar' => '082177778888',
            'alamat_pelamar' => 'Kabupaten Seluma, Bengkulu',
            'jenis_kelamin' => 'L',
        ]);

        // 3. GENERATE PERUSAHAAN FAMILIAR & LOWONGAN REALISTIS
        $this->command->info('Membuat Data Perusahaan & Lowongan Realistis...');
        $perusahaanFamiliar = $this->getDataPerusahaan();
        $lowonganIds = [];

        foreach ($perusahaanFamiliar as $data) {
            $kategori = Kategori::where('nama_kategori', $data['kategori'])->first();
            if (!$kategori) continue;

            $userMitra = User::create([
                'name' => $data['nama'],
                'email' => strtolower(str_replace([' ', '.', '(', ')'], '', $data['nama'])) . '@hrd.com',
                'password' => Hash::make('password'),
                'role' => 'mitra',
            ]);

            $mitra = Mitra::create([
                'user_id' => $userMitra->id,
                'lokasi_id' => $faker->randomElement($lokasiIds),
                'kategori_id' => $kategori->id,
                'status_mitra' => 'verified',
                'nama_mitra' => $data['nama'],
                'email_mitra' => $userMitra->email,
                'website_mitra' => 'https://www.' . strtolower(str_replace([' ', 'PT', 'Tbk', '(', ')'], '', $data['nama'])) . '.co.id',
                'tahun_berdiri' => $faker->numberBetween(1980, 2015),
                'skala_perusahaan' => $data['skala'],
                'deskripsi_mitra' => $data['deskripsi'],
                'alamat_mitra' => $faker->address,
                'nohp_mitra' => $faker->phoneNumber,
            ]);

            foreach ($data['lowongan'] as $judul) {
                $gajiMin = $faker->numberBetween(30, 50) * 100000;
                $lowongan = Lowongan::create([
                    'mitra_id' => $mitra->id,
                    'lokasi_id' => $mitra->lokasi_id,
                    'judul_lowongan' => $judul,
                    'deskripsi_lowongan' => "Bergabunglah bersama " . $data['nama'] . " sebagai $judul. Kami mencari kandidat yang disiplin dan inovatif.",
                    'tipe_pekerjaan' => $faker->randomElement(['Full-time', 'Full-time', 'Contract']),
                    'gaji_min' => $gajiMin,
                    'gaji_max' => $gajiMin + ($faker->numberBetween(10, 40) * 100000),
                    'tanggal_expired' => $faker->dateTimeBetween('now', '+2 months')->format('Y-m-d'),
                    'status_lowongan' => 'verified',
                ]);
                $lowonganIds[] = $lowongan->id;
            }
        }

        // 4. GENERATE 100 PELAMAR
        $this->command->info('Membuat 100 Data Pelamar Kerja...');
        for ($i = 0; $i < 100; $i++) {
            $user = User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password'),
                'role' => 'pelamar',
            ]);

            Pelamar::create([
                'user_id' => $user->id,
                'lokasi_id' => $faker->randomElement($lokasiIds),
                'nama_pelamar' => $user->name,
                'email_pelamar' => $user->email,
                'nohp_pelamar' => $faker->phoneNumber,
                'alamat_pelamar' => $faker->address,
                'jenis_kelamin' => $faker->randomElement(['L', 'P']),
            ]);
        }

        // AMBIL SEMUA ID SETELAH INSERT SELESAI (SOLUSI ANTI-NULL)
        $pelamarIds = Pelamar::pluck('pelamar_id')->toArray();
        $mitraIds = Mitra::pluck('id')->toArray();
        $pelamarUserIds = User::where('role', 'pelamar')->pluck('id')->toArray();

        // 5. GENERATE LAMARAN
        $this->command->info('Mengirim 400 Surat Lamaran Kerja...');
        for ($i = 0; $i < 400; $i++) {
            Lamaran::create([
                'pelamar_id' => $faker->randomElement($pelamarIds),
                'lowongan_id' => $faker->randomElement($lowonganIds),
                'status' => $faker->randomElement(['pending', 'accepted', 'rejected', 'interview'])
            ]);
        }

        // 6. GENERATE RATING & REVIEW REALISTIS
        $this->command->info('Membuat 150 Rating & Ulasan...');
        $ulasanPositif = ['HRD sangat profesional.', 'Gaji tepat waktu dan tunjangan oke.', 'Lingkungan kerja nyaman.', 'Sangat bagus untuk karir.'];
        $ulasanNegatif = ['Manajemen kurang tertata.', 'Beban kerja cukup berat.', 'Proses seleksi cukup ketat.'];

        for ($i = 0; $i < 150; $i++) {
            $bintang = $faker->numberBetween(2, 5);
            Rating::create([
                'user_id' => $faker->randomElement($pelamarUserIds),
                'mitra_id' => $faker->randomElement($mitraIds),
                'bintang' => $bintang,
                'ulasan' => ($bintang >= 4) ? $faker->randomElement($ulasanPositif) : $faker->randomElement($ulasanNegatif),
            ]);
        }

        $this->command->info('Selesai! Lokak Begawe penuh dengan data realistis.');
    }

    private function seedLokasiBengkulu(): void
    {
        $daftarLokasi = ['Kota Bengkulu', 'Bengkulu Selatan', 'Bengkulu Tengah', 'Bengkulu Utara', 'Kaur', 'Kepahiang', 'Lebong', 'Mukomuko', 'Rejang Lebong', 'Seluma'];
        foreach ($daftarLokasi as $lokasi) {
            Lokasi::updateOrCreate(['nama_lokasi' => $lokasi], ['nama_lokasi' => $lokasi]);
        }
    }

    private function seedKategoriIndustri(): void
    {
        $daftarKategori = ['Teknologi Informasi', 'Pendidikan', 'Kesehatan', 'Keuangan', 'Perdagangan', 'Produksi', 'Pariwisata', 'Konstruksi', 'Pertanian', 'UMKM'];
        foreach ($daftarKategori as $kategori) {
            Kategori::updateOrCreate(['nama_kategori' => $kategori], ['nama_kategori' => $kategori]);
        }
    }

    private function getDataPerusahaan(): array
    {
        return [
            ['nama' => 'Bank Bengkulu', 'kategori' => 'Keuangan', 'skala' => '201 - 500 Karyawan', 'deskripsi' => 'Bank Pembangunan Daerah milik pemerintah Provinsi Bengkulu.', 'lowongan' => ['Teller', 'Customer Service', 'Analyst Kredit']],
            ['nama' => 'PT Telekomunikasi Indonesia (Telkom)', 'kategori' => 'Teknologi Informasi', 'skala' => '500+ Karyawan', 'deskripsi' => 'Perusahaan informasi dan komunikasi penyedia jasa dan jaringan telekomunikasi.', 'lowongan' => ['Technical Support', 'Account Manager', 'Fiber Optic Specialist']],
            ['nama' => 'PT Indomarco Prismatama (Indomaret)', 'kategori' => 'Perdagangan', 'skala' => '500+ Karyawan', 'deskripsi' => 'Jaringan retail minimarket terbesar di Indonesia.', 'lowongan' => ['Kasir', 'Pramuniaga', 'Inventory Staff']],
            ['nama' => 'RSUD dr. M. Yunus Bengkulu', 'kategori' => 'Kesehatan', 'skala' => '500+ Karyawan', 'deskripsi' => 'Rumah sakit umum daerah rujukan utama di Provinsi Bengkulu.', 'lowongan' => ['Perawat', 'Apoteker', 'Administrasi Medis']],
            ['nama' => 'PT Astra Agro Lestari', 'kategori' => 'Pertanian', 'skala' => '500+ Karyawan', 'deskripsi' => 'Perusahaan yang bergerak di bidang perkebunan kelapa sawit.', 'lowongan' => ['Mandor Lapangan', 'Asisten Kebun', 'Operator Alat Berat']],
            ['nama' => 'Hotel Santika Bengkulu', 'kategori' => 'Pariwisata', 'skala' => '51 - 200 Karyawan', 'deskripsi' => 'Hotel bintang 3 dengan pelayanan standar internasional.', 'lowongan' => ['Receptionist', 'Housekeeping', 'Waitress']],
            ['nama' => 'Universitas Dehasen', 'kategori' => 'Pendidikan', 'skala' => '201 - 500 Karyawan', 'deskripsi' => 'Lembaga pendidikan tinggi swasta terkemuka di Kota Bengkulu.', 'lowongan' => ['Dosen Sistem Informasi', 'Staff IT', 'Admin Akademik']],
            ['nama' => 'PT Hutama Karya (Tol Trans Sumatera)', 'kategori' => 'Konstruksi', 'skala' => '500+ Karyawan', 'deskripsi' => 'BUMN yang fokus pada pengembangan infrastruktur jalan tol.', 'lowongan' => ['Civil Engineer', 'Safety Officer (K3)', 'Surveyor']],
        ];
    }
}