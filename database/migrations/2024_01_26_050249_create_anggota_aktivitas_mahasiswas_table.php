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
        Schema::create('anggota_aktivitas_mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->string('id_anggota')->nullable();
            $table->unique('id_anggota', 'unique_id_anggota');
            $table->string('id_aktivitas');
            $table->foreign('id_aktivitas')->references('id_aktivitas')->on('aktivitas_mahasiswas')->onDelete('cascade');
            $table->text('judul')->nullable();
            $table->string('id_registrasi_mahasiswa')->nullable();
            $table->foreign('id_registrasi_mahasiswa')->references('id_registrasi_mahasiswa')->on('riwayat_pendidikans')->onDelete('cascade');
            $table->unique(['id_aktivitas', 'id_registrasi_mahasiswa'], 'unique_id_aktivitas_id_registrasi_mahasiswa');
            $table->string('nim')->nullable();
            $table->string('nama_mahasiswa')->nullable();
            $table->string('jenis_peran')->nullable();
            $table->string('nama_jenis_peran')->nullable();
            $table->string('status_sync')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggota_aktivitas_mahasiswas');
    }
};
