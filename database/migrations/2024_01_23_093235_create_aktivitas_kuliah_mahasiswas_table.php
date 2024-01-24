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
        Schema::create('aktivitas_kuliah_mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->string("id_registrasi_mahasiswa")->nullable();
            $table->foreign('id_registrasi_mahasiswa')->references('id_registrasi_mahasiswa')->on('riwayat_pendidikans')->onDelete('set null');
            $table->string("nim")->nullable();
            $table->string("nama_mahasiswa")->nullable();
            $table->string("id_prodi")->nullable();
            $table->foreign('id_prodi')->references('id_prodi')->on('program_studis')->onDelete('set null');
            $table->string("nama_program_studi")->nullable();
            $table->string("angkatan")->nullable();
            $table->string("id_periode_masuk")->nullable();
            $table->foreign('id_periode_masuk')->references('id_semester')->on('semesters')->onDelete('set null');
            $table->string("id_semester")->nullable();
            $table->foreign('id_semester')->references('id_semester')->on('semesters')->onDelete('set null');
            $table->unique(['id_registrasi_mahasiswa', 'id_semester'], 'unique_id_registrasi_mahasiswa_id_semester');
            $table->string("nama_semester")->nullable();
            $table->string("id_status_mahasiswa")->nullable();
            $table->foreign('id_status_mahasiswa')->references('id_status_mahasiswa')->on('status_mahasiswas')->onDelete('set null');
            $table->string("nama_status_mahasiswa")->nullable();
            $table->string("ips")->nullable();
            $table->string("ipk")->nullable();
            $table->string("sks_semester")->nullable();
            $table->string("sks_total")->nullable();
            $table->string("biaya_kuliah_smt")->nullable();
            $table->integer("id_pembiayaan")->nullable();
            $table->foreign('id_pembiayaan')->references('id_pembiayaan')->on('pembiayaans')->onDelete('set null');
            $table->string("status_sync")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aktivitas_kuliah_mahasiswas');
    }
};
