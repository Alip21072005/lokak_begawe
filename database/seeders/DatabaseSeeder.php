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

        // ====================================================================
        // 1. MASTER DATA LOKASI & KATEGORI (Internal)
        // ====================================================================
        $this->command->info('Menyiapkan Pondasi Master Data (Lokasi & Kategori)...');
        $this->seedLokasiBengkulu();
        $this->seedKategoriIndustri();

        // ====================================================================
        // 2. MEMANGGIL MASTER DATA EKSTERNAL (Skill, Instansi, Perusahaan)
        // ====================================================================
        $this->command->info('Memanggil Seeder Master Data Eksternal...');
        $this->call([
            MasterSkillSeeder::class,
            MasterInstansiSeeder::class,
            MasterPerusahaanSeeder::class,
        ]);

        // Ambil ID dari Master Data untuk direlasikan nanti
        $lokasiIds = Lokasi::pluck('id')->toArray();
        $kategoriIds = Kategori::pluck('id')->toArray();
        $skillIds = MasterSkill::pluck('id')->toArray();
        $instansiIds = MasterInstansi::pluck('id')->toArray();
        $mPerusahaanIds = MasterPerusahaan::pluck('id')->toArray();

        // Pastikan Master Data eksternal tidak kosong
        if (empty($skillIds) || empty($instansiIds) || empty($mPerusahaanIds)) {
            $this->command->error('Tabel Master (Skill/Instansi/Perusahaan) masih kosong! Pastikan file Seeder-nya sudah diisi dengan benar.');
            return;
        }

        // ====================================================================
        // 3. AKUN UTAMA (Admin, Mitra De Code, Pelamar Asep)
        // ====================================================================
        $this->command->info('Membuat Akun Utama...');
        $admin = User::updateOrCreate(['email' => 'muhamadalipmaulana3@gmail.com'], [
            'id' => Str::uuid(),
            'name' => 'Alip Maulana (Admin)',
            'password' => Hash::make('Alip210725_'),
            'role' => 'admin',
        ]);

        $userDeCode = User::updateOrCreate(['email' => 'decode@gmail.com'], [
            'id' => Str::uuid(),
            'name' => 'De Code Community',
            'password' => Hash::make('Decode123'),
            'role' => 'mitra',
        ]);

        $mitraDeCode = Mitra::updateOrCreate(['user_id' => $userDeCode->id], [
            'id' => Str::uuid(),
            'lokasi_id' => Lokasi::where('nama_lokasi', 'Kota Bengkulu')->first()->id ?? $lokasiIds[0],
            'kategori_id' => Kategori::where('nama_kategori', 'Teknologi Informasi & Software')->first()->id ?? $kategoriIds[0],
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

        $userAsep = User::updateOrCreate(['email' => 'asepmujikno@gmail.com'], [
            'id' => Str::uuid(),
            'name' => 'Asep Mujikno',
            'password' => Hash::make('Asep12345'),
            'role' => 'pelamar',
        ]);

        $pelamarAsep = Pelamar::updateOrCreate(['user_id' => $userAsep->id], [
            'pelamar_id' => Str::uuid(),
            'lokasi_id' => Lokasi::where('nama_lokasi', 'Seluma')->first()->id ?? $lokasiIds[0],
            'nama_pelamar' => 'Asep Mujikno',
            'email_pelamar' => 'asepmujikno@gmail.com',
            'nohp_pelamar' => '082177778888',
            'alamat_pelamar' => 'Kabupaten Seluma, Bengkulu',
            'jenis_kelamin' => 'L',
            'bio' => 'Fullstack Web Developer yang antusias dengan Laravel dan Vue.js',
            'website_portfolio' => 'https://asepmujikno.com'
        ]);

        // ====================================================================
        // 4. GENERATE MITRA, LOWONGAN, SKILL LOWONGAN, & TRANSAKSI
        // ====================================================================
        $this->command->info('Membuat Data Mitra, Lowongan, dan Transaksi...');
        $perusahaanData = $this->getDataMitraRealistik();
        $lowonganIds = [];
        $semuaMitraUserIds = [$userDeCode->id]; // Simpan untuk relasi chat nanti

        foreach ($perusahaanData as $data) {
            $uMitra = User::create([
                'id' => Str::uuid(),
                'name' => $data['nama'],
                'email' => strtolower(str_replace([' ', '.', '(', ')', '-'], '', $data['nama'])) . '@hrd.com',
                'password' => Hash::make('password'),
                'role' => 'mitra',
            ]);
            $semuaMitraUserIds[] = $uMitra->id;

            $mitra = Mitra::create([
                'id' => Str::uuid(),
                'user_id' => $uMitra->id,
                'lokasi_id' => $faker->randomElement($lokasiIds),
                'kategori_id' => $faker->randomElement($kategoriIds),
                'status_mitra' => 'verified',
                'nama_mitra' => $data['nama'],
                'email_mitra' => $uMitra->email,
                'website_mitra' => 'https://www.' . strtolower(str_replace([' ', 'PT', 'Tbk', '(', ')', '-'], '', $data['nama'])) . '.co.id',
                'tahun_berdiri' => $faker->numberBetween(1990, 2015),
                'skala_perusahaan' => $data['skala'] ?? '500+ Karyawan',
                'deskripsi_mitra' => $data['deskripsi'],
                'alamat_mitra' => $faker->address,
                'nohp_mitra' => $faker->phoneNumber,
            ]);

            foreach ($data['lowongan'] as $judul) {
                $lowonganId = Str::uuid();
                $lowongan = Lowongan::create([
                    'id' => $lowonganId,
                    'mitra_id' => $mitra->id,
                    'lokasi_id' => $mitra->lokasi_id,
                    'judul_lowongan' => $judul,
                    'deskripsi_lowongan' => $faker->paragraphs(3, true),
                    'tipe_pekerjaan' => $faker->randomElement(['Full-time', 'Contract', 'Internship', 'Part-time']),
                    'gaji_min' => $faker->numberBetween(30, 50) * 100000,
                    'gaji_max' => $faker->numberBetween(60, 150) * 100000,
                    'minimal_pendidikan' => $faker->randomElement(['SMA/SMK', 'D3', 'S1', 'S2']),
                    'minimal_pengalaman' => $faker->numberBetween(0, 5),
                    'tanggal_expired' => $faker->dateTimeBetween('now', '+2 months')->format('Y-m-d'),
                    'status_lowongan' => 'verified',
                ]);

                // 4a. Transaksi / Pembayaran Paket Loker
                DB::table('transaksis')->insert([
                    'id' => Str::uuid(),
                    'lowongan_id' => $lowonganId,
                    'nama_paket' => $faker->randomElement(['Paket Basic', 'Paket Premium', 'Paket Enterprise']),
                    'harga' => $faker->randomElement([50000, 150000, 300000]),
                    'bukti_transfer' => 'bukti_dummy_' . time() . '.jpg',
                    'status_pembayaran' => 'verified',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // 4b. Lowongan Skills Pivot
                $reqSkills = (array) array_rand(array_flip($skillIds), rand(2, 5));
                foreach ($reqSkills as $sId) {
                    DB::table('lowongan_skills')->insert([
                        'id' => Str::uuid(),
                        'lowongan_id' => $lowonganId,
                        'master_skill_id' => $sId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                $lowonganIds[] = $lowonganId;
            }
        }

        // ====================================================================
        // 5. GENERATE PELAMAR (Lengkap dengan Skill, Pendidikan, Pengalaman)
        // ====================================================================
        $this->command->info('Membuat 50 Pelamar dengan Profil Super Lengkap...');
        $pelamarUserIds = [$userAsep->id];
        $pelamarIds = [$pelamarAsep->pelamar_id];

        for ($i = 0; $i < 50; $i++) {
            $user = User::create([
                'id' => Str::uuid(),
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password'),
                'role' => 'pelamar',
            ]);
            $pelamarUserIds[] = $user->id;

            $pelamarId = Str::uuid();
            $pelamarIds[] = $pelamarId;

            Pelamar::create([
                'pelamar_id' => $pelamarId,
                'user_id' => $user->id,
                'lokasi_id' => $faker->randomElement($lokasiIds),
                'nama_pelamar' => $user->name,
                'email_pelamar' => $user->email,
                'nohp_pelamar' => $faker->phoneNumber,
                'alamat_pelamar' => $faker->address,
                'jenis_kelamin' => $faker->randomElement(['L', 'P']),
                'bio' => $faker->paragraph,
                'website_portfolio' => 'https://portofolio-' . strtolower(strtok($user->name, " ")) . '.com',
            ]);

            // 5a. Skills Pelamar
            $randomSkills = (array) array_rand(array_flip($skillIds), rand(3, 7));
            foreach ($randomSkills as $sId) {
                DB::table('skills')->insert([
                    'id' => Str::uuid(),
                    'pelamar_id' => $pelamarId,
                    'master_skill_id' => $sId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // 5b. Pendidikan Pelamar
            DB::table('pendidikans')->insert([
                'id' => Str::uuid(),
                'pelamar_id' => $pelamarId,
                'master_instansi_id' => $faker->randomElement($instansiIds),
                'gelar' => $faker->randomElement(['S1 Sistem Informasi', 'S1 Teknik Informatika', 'SMA IPA', 'SMK Teknik', 'D3 Akuntansi']),
                'tgl_mulai' => $faker->dateTimeBetween('-6 years', '-4 years')->format('Y-m-d'),
                'tgl_lulus' => $faker->dateTimeBetween('-4 years', 'now')->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 5c. Pengalaman Kerja Pelamar (1-2 Pengalaman per orang)
            for ($exp = 0; $exp < rand(1, 2); $exp++) {
                $isCurrent = $exp === 0 ? true : false;
                DB::table('pengalamans')->insert([
                    'id' => Str::uuid(),
                    'pelamar_id' => $pelamarId,
                    'master_perusahaan_id' => $faker->randomElement($mPerusahaanIds),
                    'posisi' => $faker->jobTitle,
                    'tgl_mulai' => $faker->dateTimeBetween('-3 years', '-1 years')->format('Y-m-d'),
                    'tgl_selesai' => $isCurrent ? null : $faker->dateTimeBetween('-1 years', 'now')->format('Y-m-d'),
                    'is_current' => $isCurrent,
                    'deskripsi' => $faker->paragraph,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // ====================================================================
        // 6. LAMARAN, RATING, & CHAT (CONVERSATIONS + MESSAGES)
        // ====================================================================
        $this->command->info('Mengirim Lamaran, Rating, dan Percakapan Chat...');
        $allMitraIds = Mitra::pluck('id')->toArray();

        // Buat 150 Lamaran
        for ($i = 0; $i < 150; $i++) {
            $statusLamaran = $faker->randomElement(['pending', 'reviewed', 'interview', 'accepted', 'rejected']);
            Lamaran::create([
                'id' => Str::uuid(),
                'pelamar_id' => $faker->randomElement($pelamarIds),
                'lowongan_id' => $faker->randomElement($lowonganIds),
                'catatan' => 'Saya sangat tertarik dengan posisi ini dan telah melampirkan CV terbaik saya.',
                'status' => $statusLamaran,
                'catatan_mitra' => in_array($statusLamaran, ['interview', 'rejected', 'accepted']) ? 'Terima kasih atas partisipasinya. Kami akan menghubungi Anda segera.' : null,
            ]);
        }

        // Buat 100 Rating
        for ($i = 0; $i < 100; $i++) {
            Rating::create([
                'id' => Str::uuid(),
                'user_id' => $faker->randomElement($pelamarUserIds),
                'mitra_id' => $faker->randomElement($allMitraIds),
                'bintang' => $faker->biasedNumberBetween(3, 5),
                'ulasan' => $faker->randomElement(['HRD sangat ramah', 'Proses interview profesional', 'Lingkungan kerja nyaman', 'Gaji sesuai ekspektasi']),
            ]);
        }

        // Buat 30 Percakapan Chat (Conversations & Messages)
        for ($i = 0; $i < 30; $i++) {
            $pelamarSender = $faker->randomElement($pelamarUserIds);
            $mitraReceiver = $faker->randomElement($semuaMitraUserIds);

            $conversationId = Str::uuid();
            DB::table('conversations')->insert([
                'id' => $conversationId,
                'sender_id' => $pelamarSender,
                'receiver_id' => $mitraReceiver,
                'last_message_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Pesan Pertama dari Pelamar
            DB::table('messages')->insert([
                'id' => Str::uuid(),
                'conversation_id' => $conversationId,
                'sender_id' => $pelamarSender,
                'body' => 'Halo Bapak/Ibu HRD, saya ingin menanyakan status lamaran saya untuk posisi yang dibuka.',
                'read' => true,
                'type' => 'text',
                'created_at' => now()->subHours(2),
                'updated_at' => now()->subHours(2),
            ]);

            // Balasan dari Mitra
            DB::table('messages')->insert([
                'id' => Str::uuid(),
                'conversation_id' => $conversationId,
                'sender_id' => $mitraReceiver,
                'body' => 'Halo. Terima kasih atas ketertarikannya. Saat ini lamaran Anda sedang dalam tahap review oleh tim kami.',
                'read' => false,
                'type' => 'text',
                'created_at' => now()->subHour(),
                'updated_at' => now()->subHour(),
            ]);
        }

        $this->command->info('🎉 Selesai! Database Lokak Begawe sekarang sangat lengkap dan siap digunakan.');
    }

    // =======================================================================================
    // METHOD PRIVATE: LOKASI & KATEGORI (Data Dasar)
    // =======================================================================================
    private function seedLokasiBengkulu()
    {
        $daftarLokasi = ['Kota Bengkulu', 'Bengkulu Selatan', 'Bengkulu Tengah', 'Bengkulu Utara', 'Kaur', 'Kepahiang', 'Lebong', 'Mukomuko', 'Rejang Lebong', 'Seluma'];
        foreach ($daftarLokasi as $lokasi) {
            Lokasi::updateOrCreate(['nama_lokasi' => $lokasi], ['id' => Str::uuid(), 'nama_lokasi' => $lokasi]);
        }
    }

    private function seedKategoriIndustri()
    {
        $daftarKategori = [
            'Teknologi Informasi & Software',
            'Telekomunikasi',
            'E-Commerce & Digital Bisnis',
            'Perbankan & Keuangan',
            'Asuransi',
            'Akuntansi & Audit',
            'Hukum & Legal',
            'Kesehatan & Medis',
            'Farmasi',
            'Bioteknologi & Sains',
            'Pendidikan & Pengajaran',
            'Pelatihan & Kursus',
            'Desain Kreatif & Multimedia',
            'Media, Penyiaran & Jurnalisme',
            'Periklanan & Pemasaran',
            'Perhotelan & Akomodasi',
            'Pariwisata & Perjalanan',
            'Restoran, Food & Beverage',
            'Layanan Pelanggan (Customer Service)',
            'Konstruksi & Real Estate',
            'Manufaktur & Produksi',
            'Pertambangan, Minyak & Gas',
            'Energi & Sumber Daya Alam',
            'Logistik, Pergudangan & Supply Chain',
            'Transportasi & Kurir',
            'Otomotif',
            'Pertanian & Perkebunan',
            'Peternakan & Perikanan',
            'Administrasi Perkantoran',
            'Sumber Daya Manusia (HRD)',
            'Pemerintahan & Sektor Publik',
            'Perdagangan Besar & Retail',
            'Consumer Goods (FMCG)',
            'Layanan Keamanan',
            'UMKM & Kewirausahaan Lokal'
        ];
        foreach ($daftarKategori as $kategori) {
            Kategori::updateOrCreate(['nama_kategori' => $kategori], ['id' => Str::uuid(), 'nama_kategori' => $kategori]);
        }
    }

    // =======================================================================================
    // METHOD PRIVATE: DATA PERUSAHAAN FAMILIAR (Untuk Detail Mitra)
    // =======================================================================================
    private function getDataMitraRealistik(): array
    {
        return [
            [
                'nama' => 'Bank Bengkulu',
                'kategori' => 'Perbankan & Keuangan',
                'skala' => '500+ Karyawan',
                'deskripsi' => 'Bank Pembangunan Daerah (BPD) kebanggaan masyarakat Bengkulu yang berfokus pada pembangunan ekonomi regional.',
                'lowongan' => ['Frontliner (Teller/CS)', 'Account Officer', 'Analyst Kredit Mikro', 'Internal Auditor']
            ],
            [
                'nama' => 'PT Bank Mandiri (Persero) Tbk',
                'kategori' => 'Perbankan & Keuangan',
                'skala' => '500+ Karyawan',
                'deskripsi' => 'Salah satu bank BUMN terbesar di Indonesia dengan jaringan luas di Bengkulu.',
                'lowongan' => ['Banking Associate', 'Credit Operation Staff']
            ],
            [
                'nama' => 'PT Telekomunikasi Indonesia (Telkom)',
                'kategori' => 'Telekomunikasi',
                'skala' => '500+ Karyawan',
                'deskripsi' => 'Penyedia layanan telekomunikasi digital terdepan di Indonesia.',
                'lowongan' => ['Technical Support Engineer', 'Sales Specialist Indihome', 'Fiber Optic Technician']
            ],
            [
                'nama' => 'PT Indomarco Prismatama (Indomaret)',
                'kategori' => 'Perdagangan Besar & Retail',
                'skala' => '500+ Karyawan',
                'deskripsi' => 'Jaringan retail minimarket dengan distribusi terluas.',
                'lowongan' => ['Pramuniaga Toko', 'Kasir', 'Inventory Control Staff']
            ],
            [
                'nama' => 'RSUD dr. M. Yunus Bengkulu',
                'kategori' => 'Kesehatan & Medis',
                'skala' => '500+ Karyawan',
                'deskripsi' => 'Rumah sakit rujukan tertinggi di Provinsi Bengkulu.',
                'lowongan' => ['Perawat Ahli Pertama', 'Apoteker', 'Tenaga Administrasi Medis']
            ],
            [
                'nama' => 'PT Astra Agro Lestari Tbk',
                'kategori' => 'Pertanian & Perkebunan',
                'skala' => '500+ Karyawan',
                'deskripsi' => 'Perusahaan perkebunan kelapa sawit terkemuka.',
                'lowongan' => ['Asisten Kebun (Planter)', 'Mandor Produksi']
            ],
            [
                'nama' => 'Universitas Dehasen Bengkulu',
                'kategori' => 'Pendidikan & Pengajaran',
                'skala' => '201 - 500 Karyawan',
                'deskripsi' => 'Universitas swasta unggulan di Bengkulu.',
                'lowongan' => ['Dosen Fakultas Ilmu Komputer', 'Staff Administrasi Akademik']
            ],
            [
                'nama' => 'Hotel Santika Bengkulu',
                'kategori' => 'Perhotelan & Akomodasi',
                'skala' => '51 - 200 Karyawan',
                'deskripsi' => 'Hotel pilihan utama untuk bisnis dan wisata di Bengkulu.',
                'lowongan' => ['Resepsionis', 'Chef de Partie', 'Housekeeping']
            ],
            [
                'nama' => 'PT Hutama Karya (Persero)',
                'kategori' => 'Konstruksi & Real Estate',
                'skala' => '500+ Karyawan',
                'deskripsi' => 'BUMN konstruksi pengembang Tol Trans Sumatera.',
                'lowongan' => ['Civil Engineer', 'Safety Officer (K3)', 'Land Surveyor']
            ],
            [
                'nama' => 'J&T Express Bengkulu',
                'kategori' => 'Transportasi & Kurir',
                'skala' => '201 - 500 Karyawan',
                'deskripsi' => 'Perusahaan jasa pengiriman ekspres berbasis teknologi.',
                'lowongan' => ['Kurir (Sprinter)', 'Admin Drop Point']
            ],
        ];
    }
}