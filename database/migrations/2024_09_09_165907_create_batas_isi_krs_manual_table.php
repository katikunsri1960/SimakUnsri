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
        Schema::create('batas_isi_krs_manual', function (Blueprint $table) {
            $table->id();
            $table->string('id_registrasi_mahasiswa')->nullable();
            $table->foreign('id_registrasi_mahasiswa')->references('id_registrasi_mahasiswa')->on('riwayat_pendidikans')->onDelete('set null');
            $table->string('nim');
            $table->string('nama_mahasiswa');
            $table->date('batas_isi_krs');
            $table->string('status_bayar')->default(1);
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batas_isi_krs_manual');
    }
};
