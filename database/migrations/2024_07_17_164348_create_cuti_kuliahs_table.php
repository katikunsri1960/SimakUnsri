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
        Schema::create('cuti_kuliahs', function (Blueprint $table) {
            $table->id();
            $table->string('id_cuti')->unique();
            $table->string('id_registrasi_mahasiswa');
            $table->foreign('id_registrasi_mahasiswa')->references('id_registrasi_mahasiswa')->on('riwayat_pendidikans');
            $table->string('nama_mahasiswa')->nullable();
            $table->string('id_semester')->nullable();
            $table->foreign('id_semester')->references('id_semester')->on('semesters')->onDelete('cascade');
            $table->string('nama_semester')->nullable();
            $table->string('alasan_cuti');
            $table->string('file_pendukung');
            $table->boolean('approved')->default(0);
            $table->string('status_sync')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuti_kuliahs');
    }
};
