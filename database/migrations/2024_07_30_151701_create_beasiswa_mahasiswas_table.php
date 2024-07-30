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
        Schema::create('beasiswa_mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->string('id_registrasi_mahasiswa')->nullable();
            $table->foreign('id_registrasi_mahasiswa')->references('id_registrasi_mahasiswa')->on('riwayat_pendidikans')->onDelete('set null');
            $table->string('nim');
            $table->string('nama_mahasiswa');
            $table->string('id_jenis_beasiswa');
            $table->date('tanggal_mulai_beasiswa')->default('1970-01-01');
            $table->date('tanggal_akhir_beasiswa')->default('1970-01-01');
            $table->string('status_beasiswa');
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beasiswa_mahasiswas');
    }
};
