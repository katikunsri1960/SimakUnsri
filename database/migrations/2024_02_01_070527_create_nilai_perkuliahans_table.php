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
        Schema::create('nilai_perkuliahans', function (Blueprint $table) {
            $table->id();
            $table->string('id_prodi')->nullable();
            $table->foreign('id_prodi')->references('id_prodi')->on('program_studis')->onDelete('cascade');
            $table->string('nama_program_studi')->nullable();
            $table->string('id_semester')->nullable();
            $table->foreign('id_semester')->references('id_semester')->on('semesters')->onDelete('cascade');
            $table->string('nama_semester')->nullable();
            $table->string('id_matkul')->nullable();
            $table->index('id_matkul', 'idx_matkul');
            $table->string('kode_mata_kuliah')->nullable();
            $table->string('nama_mata_kuliah')->nullable();
            $table->integer('sks_mata_kuliah')->nullable();
            $table->string('id_kelas_kuliah')->nullable();
            $table->index('id_kelas_kuliah', 'idx_kelas_kuliah');
            $table->string('nama_kelas_kuliah')->nullable();
            $table->string('id_registrasi_mahasiswa')->nullable();
            $table->foreign('id_registrasi_mahasiswa')->references('id_registrasi_mahasiswa')->on('riwayat_pendidikans')->onDelete('cascade');
            $table->unique(['id_kelas_kuliah', 'id_registrasi_mahasiswa'], 'unique_kelas_registrasi');
            $table->string('id_mahasiswa')->nullable();
            $table->string('nim')->nullable();
            $table->string('nama_mahasiswa')->nullable();
            $table->string('jurusan')->nullable();
            $table->string('angkatan')->nullable();
            $table->float('nilai_angka', 8, 1)->nullable();
            $table->float('nilai_indeks', 8, 2)->nullable();
            $table->string('nilai_huruf')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_perkuliahans');
    }
};
