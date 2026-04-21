<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Lokasi;
use App\Models\Kategori;
use App\Models\Mitra;
use App\Models\Pelamar;
use App\Models\Lowongan;
use App\Models\Lamaran;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Gunakan Faker Bahasa Indonesia
        $faker = Faker::create('id_ID');

        // ==========================================
        // 1. SEEDER LOKASI & KATEGORI
        // ==========================================
        $this->seedLokasiBengkulu();
        $this->seedKategoriIndustri();

        $lokasiIds = Lokasi::pluck('id')->toArray();
        $kategoriIds = Kategori::pluck('id')->toArray();

        // ==========================================
        // 2. SEEDER AKUN UTAMA (DEFAULT)
        // ==========================================
        User::updateOrCreate(['email' => 'muhamadalipmaulana3@gmail.com'], [
            'name' => 'Alip Maulana',
            'password' => Hash::make('Alip210725_'),
            'role' => 'admin',
        ]);

        User::updateOrCreate(['email' => 'admin@mail.com'], [
            'name' => 'Admin Utama',
            'password' => Hash::make('admin'),
            'role' => 'admin',
        ]);

        $userMitra = User::updateOrCreate(['email' => 'mitra@mail.com'], [
            'name' => 'Mitra Tester',
            'password' => Hash::make('mitra'),
            'role' => 'mitra',
        ]);
        Mitra::updateOrCreate(['user_id' => $userMitra->id], [
            'lokasi_id' => $faker->randomElement($lokasiIds),
            'kategori_id' => $faker->randomElement($kategoriIds),
            'status_mitra' => 'verified',
            'nama_mitra' => 'PT Makmur Sejahtera (Akun Default)',
            'email_mitra' => 'mitra@mail.com',
            'deskripsi_mitra' => 'Perusahaan default untuk testing fitur dashboard mitra. Kami bergerak di bidang teknologi dan inovasi digital untuk masa depan.',
            'alamat_mitra' => 'Jl. P. Natadirja, Kota Bengkulu',
            'nohp_mitra' => '081234567890',
        ]);

        $userPelamar = User::updateOrCreate(['email' => 'pelamar@mail.com'], [
            'name' => 'Pelamar Tester',
            'password' => Hash::make('pelamar'),
            'role' => 'pelamar',
        ]);
        Pelamar::updateOrCreate(['user_id' => $userPelamar->id], [
            'lokasi_id' => $faker->randomElement($lokasiIds),
            'nama_pelamar' => 'Budi Santoso (Akun Default)',
            'email_pelamar' => 'pelamar@mail.com',
            'nohp_pelamar' => '082188889999',
            'alamat_pelamar' => 'Jl. R.E. Martadinata, Kota Bengkulu',
            'jenis_kelamin' => 'L',
        ]);

        // ==========================================
        // 3. GENERATE 50 DUMMY MITRA (PERUSAHAAN)
        // ==========================================
        $mitraUsers = [];
        // Kita pecah command ini agar terminal kamu ada sedikit progres jika butuh waktu
        $this->command->info('Membuat 50 Data Perusahaan (Mitra)...');
        for ($i = 0; $i < 50; $i++) {
            $user = User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->companyEmail,
                'password' => Hash::make('password'),
                'role' => 'mitra',
            ]);

            $mitra = Mitra::create([
                'user_id' => $user->id,
                'lokasi_id' => $faker->randomElement($lokasiIds),
                'kategori_id' => $faker->randomElement($kategoriIds),
                'status_mitra' => $faker->randomElement(['verified', 'verified', 'verified', 'pending']), // Lebih banyak yang verified
                'nama_mitra' => $faker->company,
                'email_mitra' => $user->email,
                'website_mitra' => 'https://www.' . $faker->domainName,
                'deskripsi_mitra' => $faker->paragraph(rand(2, 4)),
                'alamat_mitra' => $faker->address,
                'nohp_mitra' => $faker->phoneNumber,
            ]);

            // Masukkan ke array hanya mitra yang verified agar bisa bikin lowongan
            if ($mitra->status_mitra === 'verified') {
                $mitraUsers[] = $mitra->id;
            }
        }

        // ==========================================
        // 4. GENERATE 100 DUMMY PELAMAR
        // ==========================================
        $this->command->info('Membuat 100 Data Pelamar Kerja...');
        $pelamarUsers = [];
        for ($i = 0; $i < 100; $i++) {
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
            ]);
            $pelamarUsers[] = $pelamar->pelamar_id;
        }
        $pelamarUsers[] = $userPelamar->pelamar->pelamar_id;

        // ==========================================
        // 5. GENERATE 200 DUMMY LOWONGAN
        // ==========================================
        $this->command->info('Menerbitkan 200 Lowongan Pekerjaan...');
        $judulPekerjaan = [
            'Frontend Vue Developer',
            'Backend Laravel Developer',
            'UI/UX Designer',
            'Mobile App Developer (Flutter)',
            'Fullstack Web Programmer',
            'Staff Administrasi',
            'Customer Service Representative',
            'Marketing Executive',
            'Data Analyst',
            'Cyber Security Admin',
            'DevOps Engineer',
            'Network Engineer',
            'Social Media Specialist',
            'Graphic Designer',
            'Content Creator',
            'Human Resources (HRD) Staff',
            'Sales Supervisor',
            'Accounting Staff',
            'Project Manager',
            'Business Analyst',
            'System Administrator',
            'Quality Assurance (QA) Tester',
            'Digital Marketing Lead',
            'Video Editor',
            'Copywriter'
        ];

        $lowonganIds = [];
        $semuaMitraId = array_merge($mitraUsers, [$userMitra->mitra->id]);

        for ($i = 0; $i < 200; $i++) {
            $gajiMin = $faker->numberBetween(15, 60) * 100000; // 1.5jt - 6jt
            $gajiMax = $gajiMin + ($faker->numberBetween(10, 80) * 100000); // Gaji min ditambah 1jt - 8jt

            $lowongan = Lowongan::create([
                'mitra_id' => $faker->randomElement($semuaMitraId),
                'lokasi_id' => $faker->randomElement($lokasiIds),
                'judul_lowongan' => $faker->randomElement($judulPekerjaan),
                'deskripsi_lowongan' => $faker->paragraphs(rand(2, 5), true),
                'tipe_pekerjaan' => $faker->randomElement(['Full-time', 'Full-time', 'Part-time', 'Internship', 'Contract']),
                'gaji_min' => $gajiMin,
                'gaji_max' => $gajiMax,
                'tanggal_expired' => $faker->dateTimeBetween('now', '+3 months')->format('Y-m-d'),
                'status_lowongan' => $faker->randomElement(['verified', 'verified', 'verified', 'verified', 'pending']),
            ]);

            if ($lowongan->status_lowongan === 'verified') {
                $lowonganIds[] = $lowongan->id;
            }
        }

        // ==========================================
        // 6. GENERATE 500 DUMMY LAMARAN
        // ==========================================
        $this->command->info('Mengirim 500 Surat Lamaran Kerja...');
        for ($i = 0; $i < 500; $i++) {
            $pelamarId = $faker->randomElement($pelamarUsers);
            $lowonganId = $faker->randomElement($lowonganIds);

            Lamaran::updateOrCreate(
                ['pelamar_id' => $pelamarId, 'lowongan_id' => $lowonganId],
                ['status' => $faker->randomElement(['pending', 'pending', 'accepted', 'rejected'])] // Pending dibanyakin
            );
        }

        $this->command->info('Selesai! Database sekarang penuh dengan data.');
    }

    // ---------------------------------------------------------
    // FUNGSI HELPER
    // ---------------------------------------------------------
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
            Lokasi::updateOrCreate(['nama_lokasi' => $lokasi], ['nama_lokasi' => $lokasi]);
        }
    }

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
            Kategori::updateOrCreate(['nama_kategori' => $kategori], ['nama_kategori' => $kategori]);
        }
    }
}