<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MasterPerusahaanSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Membangun Database Master Perusahaan Nasional & Lokal...');

        $perusahaans = [
            // ==========================================
            // 1. TEKNOLOGI, STARTUP & E-COMMERCE
            // ==========================================
            ['nama' => 'PT GoTo Gojek Tokopedia Tbk', 'bidang' => 'Teknologi & E-Commerce'],
            ['nama' => 'PT Shopee International Indonesia', 'bidang' => 'E-Commerce'],
            ['nama' => 'PT Traveloka Indonesia', 'bidang' => 'Teknologi & Perjalanan'],
            ['nama' => 'PT Bukalapak.com Tbk', 'bidang' => 'E-Commerce'],
            ['nama' => 'PT Ruang Raya Indonesia (Ruangguru)', 'bidang' => 'Teknologi Pendidikan'],
            ['nama' => 'PT Telekomunikasi Selular (Telkomsel)', 'bidang' => 'Telekomunikasi'],
            ['nama' => 'PT Indosat Tbk (Indosat Ooredoo Hutchison)', 'bidang' => 'Telekomunikasi'],
            ['nama' => 'PT XL Axiata Tbk', 'bidang' => 'Telekomunikasi'],

            // ==========================================
            // 2. BUMN & INSTANSI PEMERINTAH
            // ==========================================
            ['nama' => 'PT Pertamina (Persero)', 'bidang' => 'Minyak & Gas'],
            ['nama' => 'PT Perusahaan Listrik Negara (PLN)', 'bidang' => 'Energi'],
            ['nama' => 'PT Telekomunikasi Indonesia (Telkom)', 'bidang' => 'Telekomunikasi'],
            ['nama' => 'PT Kereta Api Indonesia (KAI)', 'bidang' => 'Transportasi'],
            ['nama' => 'PT Garuda Indonesia (Persero) Tbk', 'bidang' => 'Penerbangan'],
            ['nama' => 'PT Pelabuhan Indonesia (Pelindo)', 'bidang' => 'Logistik & Maritim'],
            ['nama' => 'PT Jasa Marga (Persero) Tbk', 'bidang' => 'Infrastruktur'],
            ['nama' => 'PT Pos Indonesia (Persero)', 'bidang' => 'Logistik'],

            // ==========================================
            // 3. PERBANKAN & KEUANGAN
            // ==========================================
            ['nama' => 'PT Bank Mandiri (Persero) Tbk', 'bidang' => 'Perbankan'],
            ['nama' => 'PT Bank Rakyat Indonesia (BRI) Tbk', 'bidang' => 'Perbankan'],
            ['nama' => 'PT Bank Central Asia (BCA) Tbk', 'bidang' => 'Perbankan'],
            ['nama' => 'PT Bank Negara Indonesia (BNI) Tbk', 'bidang' => 'Perbankan'],
            ['nama' => 'PT Bank Tabungan Negara (BTN) Tbk', 'bidang' => 'Perbankan'],
            ['nama' => 'PT Bank Syariah Indonesia (BSI) Tbk', 'bidang' => 'Perbankan Syariah'],
            ['nama' => 'PT Bank Mega Tbk', 'bidang' => 'Perbankan'],

            // ==========================================
            // 4. FMCG, RETAIL & MANUFAKTUR
            // ==========================================
            ['nama' => 'PT Indofood Sukses Makmur Tbk', 'bidang' => 'FMCG (Consumer Goods)'],
            ['nama' => 'PT Unilever Indonesia Tbk', 'bidang' => 'FMCG (Consumer Goods)'],
            ['nama' => 'PT Mayora Indah Tbk', 'bidang' => 'FMCG (Consumer Goods)'],
            ['nama' => 'PT Indomarco Prismatama (Indomaret)', 'bidang' => 'Retail'],
            ['nama' => 'PT Sumber Alfaria Trijaya Tbk (Alfamart)', 'bidang' => 'Retail'],
            ['nama' => 'PT Astra International Tbk', 'bidang' => 'Otomotif & Konglomerasi'],
            ['nama' => 'Wings Group Indonesia', 'bidang' => 'FMCG (Consumer Goods)'],
            ['nama' => 'PT Djarum', 'bidang' => 'Manufaktur'],
            ['nama' => 'Gudang Garam Tbk', 'bidang' => 'Manufaktur'],

            // ==========================================
            // 5. TAMBANG, AGRO & KONSTRUKSI
            // ==========================================
            ['nama' => 'PT Freeport Indonesia', 'bidang' => 'Pertambangan'],
            ['nama' => 'PT Bukit Asam Tbk', 'bidang' => 'Pertambangan'],
            ['nama' => 'PT Adaro Energy Tbk', 'bidang' => 'Pertambangan'],
            ['nama' => 'PT Astra Agro Lestari Tbk', 'bidang' => 'Pertanian & Perkebunan'],
            ['nama' => 'PT Waskita Karya (Persero) Tbk', 'bidang' => 'Konstruksi'],
            ['nama' => 'PT Wijaya Karya (Persero) Tbk', 'bidang' => 'Konstruksi'],
            ['nama' => 'PT Hutama Karya (Persero)', 'bidang' => 'Konstruksi'],

            // ==========================================
            // 6. FOKUS LOKAL BENGKULU & KOMUNITAS
            // ==========================================
            ['nama' => 'Bank Bengkulu', 'bidang' => 'Perbankan Daerah'],
            ['nama' => 'Bengkulu Ekspress Media Group', 'bidang' => 'Media & Penyiaran'],
            ['nama' => 'RSUD dr. M. Yunus Bengkulu', 'bidang' => 'Kesehatan & Medis'],
            ['nama' => 'Bengkulu Indah Mall (BIM)', 'bidang' => 'Manajemen Properti & Retail'],
            ['nama' => 'PT Pelindo (Persero) Regional 2 Pulau Baai Bengkulu', 'bidang' => 'Logistik & Maritim'],
            ['nama' => 'PT Aranus', 'bidang' => 'Teknologi Informasi & Software'],
            ['nama' => 'De Code Community', 'bidang' => 'Komunitas IT & Pengembangan Software'],

            // ==========================================
            // 7. CATCH-ALL (PILIHAN "LAINNYA")
            // ==========================================
            ['nama' => 'Perusahaan BUMN Lainnya', 'bidang' => 'Lainnya'],
            ['nama' => 'Instansi Pemerintahan / Kementerian Lainnya', 'bidang' => 'Pemerintahan'],
            ['nama' => 'Perusahaan Swasta Nasional Lainnya', 'bidang' => 'Lainnya'],
            ['nama' => 'Perusahaan Multinasional (PMA) Lainnya', 'bidang' => 'Lainnya'],
            ['nama' => 'Perusahaan Startup / Teknologi Lainnya', 'bidang' => 'Lainnya'],
            ['nama' => 'UMKM / Bisnis Lokal Lainnya', 'bidang' => 'Lainnya'],
            ['nama' => 'Instansi Pendidikan Lainnya', 'bidang' => 'Pendidikan'],
            ['nama' => 'Klinik / Rumah Sakit / Instansi Kesehatan Lainnya', 'bidang' => 'Kesehatan'],
            ['nama' => 'Lembaga Swadaya Masyarakat (NGO) Lainnya', 'bidang' => 'Non-Profit'],
            ['nama' => 'Belum Memiliki Pengalaman Kerja (Fresh Graduate)', 'bidang' => 'Tidak Ada'],
        ];

        $insertedCount = 0;

        foreach ($perusahaans as $data) {
            $exists = DB::table('master_perusahaans')->where('nama_perusahaan', $data['nama'])->exists();

            if (!$exists) {
                DB::table('master_perusahaans')->insert([
                    'id' => (string) Str::uuid(),
                    'nama_perusahaan' => $data['nama'],
                    'bidang_usaha' => $data['bidang'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $insertedCount++;
            }
        }

        $this->command->info("Selesai! {$insertedCount} Master Perusahaan berhasil ditambahkan ke database.");
    }
}