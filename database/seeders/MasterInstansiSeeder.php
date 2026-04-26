<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MasterInstansiSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Membangun Database Instansi Pendidikan (Universitas, SMA, SMK)...');

        $instansis = [];

        // ====================================================================
        // 1. DATA UNIVERSITAS / INSTITUT / POLITEKNIK (NASIONAL & BENGKULU)
        // ====================================================================
        $universitas = [
            // PTN Top Nasional
            'Universitas Indonesia (UI)',
            'Universitas Gadjah Mada (UGM)',
            'Institut Teknologi Bandung (ITB)',
            'IPB University',
            'Universitas Airlangga (UNAIR)',
            'Universitas Padjadjaran (UNPAD)',
            'Universitas Diponegoro (UNDIP)',
            'Universitas Brawijaya (UB)',
            'Universitas Sebelas Maret (UNS)',
            'Universitas Hasanuddin (UNHAS)',
            'Universitas Sumatera Utara (USU)',
            'Universitas Andalas (UNAND)',
            'Universitas Sriwijaya (UNSRI)',
            'Universitas Syiah Kuala (USK)',
            'Universitas Pendidikan Indonesia (UPI)',
            'Universitas Negeri Jakarta (UNJ)',
            'Universitas Negeri Yogyakarta (UNY)',
            'Universitas Negeri Malang (UM)',
            'Institut Teknologi Sepuluh Nopember (ITS)',
            'UIN Syarif Hidayatullah Jakarta',
            'UIN Sunan Kalijaga Yogyakarta',

            // Fokus Lokal Bengkulu (Wajib ada untuk Lokak Begawe)
            'Universitas Bengkulu (UNIB)',
            'Universitas Dehasen Bengkulu (UNIVED)',
            'UIN Fatmawati Sukarno Bengkulu',
            'Universitas Muhammadiyah Bengkulu (UMB)',
            'Universitas Prof. Dr. Hazairin, SH (UNIHAZ)',
            'Politeknik Kesehatan Kemenkes Bengkulu',
            'STIA Bengkulu',
            'STIKES Tri Mandiri Sakti Bengkulu',
            'Sekolah Tinggi Ilmu Ekonomi (STIE) Bengkulu',

            // PTS Top Nasional
            'BINUS University',
            'Telkom University',
            'Universitas Trisakti',
            'Universitas Tarumanagara (UNTAR)',
            'Universitas Pelita Harapan (UPH)',
            'Universitas Gunadarma',
            'Universitas Islam Indonesia (UII)',
            'Universitas Muhammadiyah Malang (UMM)',
            'Universitas Muhammadiyah Yogyakarta (UMY)',
            'Universitas Katolik Parahyangan (UNPAR)',
            'Universitas Sanata Dharma',
            'Universitas Atma Jaya',

            // Politeknik & Kedinasan
            'Politeknik Negeri Jakarta (PNJ)',
            'Politeknik Negeri Bandung (POLBAN)',
            'Politeknik Negeri Malang (POLINEMA)',
            'Politeknik Negeri Sriwijaya (POLSRI)',
            'Politeknik Keuangan Negara STAN',
            'Politeknik Statistika STIS',
            'Institut Pemerintahan Dalam Negeri (IPDN)',
            'Sekolah Tinggi Meteorologi Klimatologi dan Geofisika (STMKG)'
        ];

        foreach ($universitas as $nama) {
            $instansis[] = ['nama_instansi' => $nama, 'jenis_instansi' => 'Universitas'];
        }

        // ====================================================================
        // 2. DATA SMA / SMK / MA (GENERALISASI & LOKAL BENGKULU)
        // ====================================================================

        // Membangun list nama sekolah yang general (Biasanya setiap daerah punya SMAN 1 sampai SMAN 15)
        for ($i = 1; $i <= 15; $i++) {
            $instansis[] = ['nama_instansi' => "SMA Negeri $i", 'jenis_instansi' => 'SMA'];
            $instansis[] = ['nama_instansi' => "SMK Negeri $i", 'jenis_instansi' => 'SMK'];
            $instansis[] = ['nama_instansi' => "SMP Negeri $i", 'jenis_instansi' => 'SMP']; // Ekstra jika dibutuhkan
        }

        $sekolahSpesifik = [
            // SMA/SMK Top Nasional
            'SMA Taruna Nusantara',
            'SMA Krida Nusantara',
            'SMA MH Thamrin Jakarta',
            'SMAN 8 Jakarta',
            'SMAN 3 Bandung',
            'SMAN 5 Surabaya',
            'SMK Telkom Malang',
            'SMK Telkom Purwokerto',

            // Spesifik Kota Bengkulu
            'SMA Negeri 1 Kota Bengkulu',
            'SMA Negeri 2 Kota Bengkulu',
            'SMA Negeri 5 Kota Bengkulu',
            'SMK Negeri 1 Kota Bengkulu',
            'SMK Negeri 2 Kota Bengkulu',
            'SMK Negeri 3 Kota Bengkulu',
            'MAN 1 Kota Bengkulu',
            'MAN 2 Kota Bengkulu',
            'SMA Sint Carolus Bengkulu',
            'SMA Muhammadiyah 4 Bengkulu',

            // Format MA (Madrasah Aliyah)
            'MAN Insan Cendekia',
            'Madrasah Aliyah Negeri (MAN) 1',
            'Madrasah Aliyah Negeri (MAN) 2',
            'Madrasah Aliyah Swasta (MAS)'
        ];

        foreach ($sekolahSpesifik as $nama) {
            $jenis = str_contains($nama, 'SMK') ? 'SMK' : 'SMA';
            $instansis[] = ['nama_instansi' => $nama, 'jenis_instansi' => $jenis];
        }

        // ====================================================================
        // 3. DATA KURSUS / BOOTCAMP
        // ====================================================================
        $kursus = [
            'Hacktiv8',
            'Purwadhika Digital Technology School',
            'Binar Academy',
            'Rakamin Academy',
            'Dicoding Indonesia',
            'Camp404',
            'Phincon Academy',
            'Cyber Academy Indonesia',
            'Ruangguru Camp',
            'HarukaEdu / Pintaria'
        ];

        foreach ($kursus as $nama) {
            $instansis[] = ['nama_instansi' => $nama, 'jenis_instansi' => 'Kursus'];
        }

        // ====================================================================
        // 4. PILIHAN "LAINNYA" (CATCH-ALL) - SANGAT PENTING!
        // ====================================================================
        $lainnya = [
            ['nama_instansi' => 'Universitas / Institut / Politeknik Lainnya', 'jenis_instansi' => 'Universitas'],
            ['nama_instansi' => 'SMA Negeri Lainnya', 'jenis_instansi' => 'SMA'],
            ['nama_instansi' => 'SMA Swasta Lainnya', 'jenis_instansi' => 'SMA'],
            ['nama_instansi' => 'SMK Negeri Lainnya', 'jenis_instansi' => 'SMK'],
            ['nama_instansi' => 'SMK Swasta Lainnya', 'jenis_instansi' => 'SMK'],
            ['nama_instansi' => 'Madrasah Aliyah (MA) Lainnya', 'jenis_instansi' => 'SMA'],
            ['nama_instansi' => 'Lembaga Kursus / Bootcamp Lainnya', 'jenis_instansi' => 'Kursus'],
            ['nama_instansi' => 'Pendidikan di Luar Negeri', 'jenis_instansi' => 'Universitas'],
        ];

        $instansis = array_merge($instansis, $lainnya);

        // ====================================================================
        // PROSES INSERT KE DATABASE
        // ====================================================================
        $insertedCount = 0;

        foreach ($instansis as $data) {
            $exists = DB::table('master_instansis')->where('nama_instansi', $data['nama_instansi'])->exists();

            if (!$exists) {
                DB::table('master_instansis')->insert([
                    'id' => (string) Str::uuid(),
                    'nama_instansi' => $data['nama_instansi'],
                    'jenis_instansi' => $data['jenis_instansi'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $insertedCount++;
            }
        }

        $this->command->info("Selesai! {$insertedCount} Instansi Pendidikan berhasil ditambahkan ke database.");
    }
}