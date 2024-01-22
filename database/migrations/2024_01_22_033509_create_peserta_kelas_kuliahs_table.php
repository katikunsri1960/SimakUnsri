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
        Schema::create('peserta_kelas_kuliahs', function (Blueprint $table) {
            $table->id();
            $table->string("id_kelas_kuliah")->nullable();
            $table->foreign('id_kelas_kuliah')->references('id_kelas_kuliah')->on('kelas_kuliahs')->onDelete('set null');
            $table->string("nama_kelas_kuliah")->nullable();
            $table->string("id_registrasi_mahasiswa")->nullable();
            $table->foreign('id_registrasi_mahasiswa')->references('id_registrasi_mahasiswa')->on('riwayat_pendidikans')->onDelete('set null');
            $table->unique(['id_kelas_kuliah', 'id_registrasi_mahasiswa'], 'unique_peserta_kelas_kuliah');
            $table->string("id_mahasiswa")->nullable();
            $table->index('id_mahasiswa', 'idx_mahasiswa');
            $table->string("nim")->nullable();
            $table->string("nama_mahasiswa")->nullable();
            $table->string("id_matkul")->nullable();
            $table->string("kode_mata_kuliah")->nullable();
            $table->string("nama_mata_kuliah")->nullable();
            $table->string("id_prodi")->nullable();
            $table->foreign('id_prodi')->references('id_prodi')->on('program_studis')->onDelete('set null');
            $table->string("nama_program_studi")->nullable();
            $table->string("angkatan")->nullable();
            $table->string("status_sync")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peserta_kelas_kuliahs');
    }
};
