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
        Schema::create('riwayat_pendidikan_dosens', function (Blueprint $table) {
            $table->id();
            $table->string('id_dosen')->nullable();
            $table->index('id_dosen', 'idx_id_dosen');
            $table->string('nidn')->nullable();
            $table->string('nama_dosen')->nullable();
            $table->integer('id_bidang_studi')->nullable();
            $table->text('nama_bidang_studi')->nullable();
            $table->string('id_jenjang_pendidikan')->nullable();
            $table->unique(['id_dosen', 'id_jenjang_pendidikan', 'id_bidang_studi'], 'idx_id_dosen_id_jenjang_pendidikan');
            $table->string('nama_jenjang_pendidikan')->nullable();
            $table->integer('id_gelar_akademik')->nullable();
            $table->string('nama_gelar_akademik')->nullable();
            $table->string('id_perguruan_tinggi')->nullable();
            $table->text('nama_perguruan_tinggi')->nullable();
            $table->string('fakultas')->nullable();
            $table->string('tahun_lulus')->nullable();
            $table->string('sks_lulus')->nullable();
            $table->string('ipk')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_pendidikan_dosens');
    }
};
