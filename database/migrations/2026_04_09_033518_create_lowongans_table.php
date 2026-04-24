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
        // 1. Membuat Tabel Lowongan Utama
        Schema::create('lowongans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('mitra_id')->constrained('mitras')->onDelete('cascade');
            $table->foreignUuid('lokasi_id')->constrained('lokasis')->onDelete('cascade');

            $table->string('judul_lowongan');
            $table->text('deskripsi_lowongan');
            $table->string('tipe_pekerjaan'); // Full-time, Part-time, dll

            $table->bigInteger('gaji_min')->default(0);
            $table->bigInteger('gaji_max')->default(0);

            // --- INTEGRASI SYARAT ATS (Applicant Tracking System) ---
            $table->string('minimal_pendidikan')->nullable(); // SMA/SMK, D3, S1
            $table->integer('minimal_pengalaman')->default(0); // Dalam satuan tahun

            $table->date('tanggal_expired')->nullable();
            $table->enum('status_lowongan', ['pending', 'verified', 'rejected'])->default('pending');

            $table->timestamps();
        });

        // 2. Membuat Tabel Pivot Skill (Menyatu di sini agar urutan FK benar)
        Schema::create('lowongan_skills', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('lowongan_id')->constrained('lowongans')->onDelete('cascade');
            $table->foreignUuid('master_skill_id')->constrained('master_skills')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('lowongan_skills');
        Schema::dropIfExists('lowongans');
        Schema::enableForeignKeyConstraints();
    }
};
