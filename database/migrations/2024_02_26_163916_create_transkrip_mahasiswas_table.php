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
        Schema::create('transkrip_mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->boolean('feeder')->default(1);
            $table->string('id_registrasi_mahasiswa');
            $table->foreign('id_registrasi_mahasiswa')->references('id_registrasi_mahasiswa')->on('riwayat_pendidikans');
            $table->string('id_matkul');
            $table->foreign('id_matkul')->references('id_matkul')->on('mata_kuliahs');
            $table->unique(['id_registrasi_mahasiswa', 'id_matkul']);
            $table->string('id_kelas_kuliah')->nullable();
            $table->index('id_kelas_kuliah', 'idx_kelas_kuliah');
            $table->string('id_nilai_transfer')->nullable();
            $table->foreign('id_nilai_transfer')->references('id_transfer')->on('nilai_transfer_pendidikans');
            $table->string('id_konversi_aktivitas')->nullable();
            $table->foreign('id_konversi_aktivitas')->references('id_konversi_aktivitas')->on('konversi_aktivitas');
            $table->string('smt_diambil')->nullable();
            $table->string('kode_mata_kuliah')->nullable();
            $table->string('nama_mata_kuliah')->nullable();
            $table->string('sks_mata_kuliah')->nullable();
            $table->float('nilai_angka')->nullable();
            $table->string('nilai_huruf')->nullable();
            $table->float('nilai_indeks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transkrip_mahasiswas');
    }
};
