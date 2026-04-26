<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/2026_04_09_033518_create_lowongans_table.php

    public function up(): void
    {
        Schema::create('lowongans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('mitra_id')->constrained('mitras')->onDelete('cascade');
            $table->foreignUuid('lokasi_id')->constrained('lokasis')->onDelete('cascade');

            $table->string('judul_lowongan');
            $table->text('deskripsi_lowongan');
            $table->string('tipe_pekerjaan');

            $table->bigInteger('gaji_min')->default(0);
            $table->bigInteger('gaji_max')->default(0);

            $table->string('minimal_pendidikan')->nullable();
            $table->integer('minimal_pengalaman')->default(0);

            $table->date('tanggal_expired')->nullable();
            $table->enum('status_lowongan', ['pending', 'verified', 'rejected'])->default('pending');

            $table->timestamps();
        });

        Schema::create('lowongan_skills', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('lowongan_id')->constrained('lowongans')->onDelete('cascade');
            // Pastikan tabel master_skills sudah dibuat di migrasi sebelumnya!
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