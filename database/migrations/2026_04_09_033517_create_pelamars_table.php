<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. TABEL UTAMA PELAMAR
        Schema::create('pelamars', function (Blueprint $table) {
            $table->uuid('pelamar_id')->primary();
            $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignUuid('lokasi_id')->nullable()->constrained('lokasis')->onDelete('set null');

            $table->string('nama_pelamar')->nullable();
            $table->string('email_pelamar')->nullable();
            $table->string('nohp_pelamar')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->text('alamat_pelamar')->nullable();
            $table->text('bio')->nullable();
            $table->string('website_portfolio')->nullable();
            $table->string('foto_pelamar')->nullable();
            $table->string('cv_pelamar')->nullable();
            $table->timestamps();
        });

        // 2. TABEL SKILLS PELAMAR
        Schema::create('skills', function (Blueprint $table) {
            $table->uuid('id')->primary();
            // Perhatikan: Karena parent key-nya 'pelamar_id', kita harus spesifik di constrained()
            $table->foreignUuid('pelamar_id')->constrained('pelamars', 'pelamar_id')->onDelete('cascade');
            $table->foreignUuid('master_skill_id')->constrained('master_skills')->onDelete('cascade');
            $table->timestamps();
        });

        // 3. TABEL PENGALAMAN KERJA
        Schema::create('pengalamans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('pelamar_id')->constrained('pelamars', 'pelamar_id')->onDelete('cascade');
            $table->foreignUuid('master_perusahaan_id')->nullable()->constrained('master_perusahaans')->onDelete('set null');
            $table->string('posisi')->nullable();
            $table->date('tgl_mulai')->nullable();
            $table->date('tgl_selesai')->nullable();
            $table->boolean('is_current')->default(false);
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        // 4. TABEL PENDIDIKAN
        Schema::create('pendidikans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('pelamar_id')->constrained('pelamars', 'pelamar_id')->onDelete('cascade');
            $table->foreignUuid('master_instansi_id')->nullable()->constrained('master_instansis')->onDelete('set null');
            $table->string('gelar')->nullable(); // Contoh: S1 Sistem Informasi
            $table->date('tgl_mulai')->nullable();
            $table->date('tgl_lulus')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendidikans');
        Schema::dropIfExists('pengalamans');
        Schema::dropIfExists('skills');
        Schema::dropIfExists('pelamars');
    }
};
