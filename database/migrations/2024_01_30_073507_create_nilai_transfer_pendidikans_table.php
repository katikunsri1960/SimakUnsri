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
        Schema::create('nilai_transfer_pendidikans', function (Blueprint $table) {
            $table->id();
            $table->string('id_transfer')->unique();
            $table->string('id_registrasi_mahasiswa');
            $table->foreign('id_registrasi_mahasiswa')->references('id_registrasi_mahasiswa')->on('riwayat_pendidikans')->onDelete('cascade');
            $table->string('nim')->nullable();
            $table->string('nama_mahasiswa')->nullable();
            $table->string('id_prodi');
            $table->foreign('id_prodi')->references('id_prodi')->on('program_studis')->onDelete('cascade');
            $table->string('nama_program_studi')->nullable();
            $table->string('id_periode_masuk');
            $table->foreign('id_periode_masuk')->references('id_semester')->on('semesters')->onDelete('cascade');
            $table->string('kode_mata_kuliah_asal')->nullable();
            $table->string('nama_mata_kuliah_asal')->nullable();
            $table->integer('sks_mata_kuliah_asal')->nullable();
            $table->string('nilai_huruf_asal')->nullable();
            $table->string('id_matkul');
            $table->foreign('id_matkul')->references('id_matkul')->on('mata_kuliahs')->onDelete('cascade');
            $table->string('kode_matkul_diakui')->nullable();
            $table->string('nama_mata_kuliah_diakui')->nullable();
            $table->integer('sks_mata_kuliah_diakui')->nullable();
            $table->string('nilai_huruf_diakui')->nullable();
            $table->float('nilai_angka_diakui', 8, 2)->nullable();
            $table->string('id_perguruan_tinggi')->nullable();
            $table->string('id_aktivitas')->nullable();
            $table->foreign('id_aktivitas')->references('id_aktivitas')->on('aktivitas_mahasiswas')->onDelete('cascade');
            $table->text('judul')->nullable();
            $table->integer('id_jenis_aktivitas')->nullable();
            $table->foreign('id_jenis_aktivitas')->references('id_jenis_aktivitas_mahasiswa')->on('jenis_aktivitas_mahasiswas')->onDelete('cascade');
            $table->string('nama_jenis_aktivitas')->nullable();
            $table->string('id_semester')->nullable();
            $table->foreign('id_semester')->references('id_semester')->on('semesters')->onDelete('cascade');
            $table->string('nama_semester')->nullable();
            $table->string('status_sync')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_transfer_pendidikans');
    }
};
