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
        Schema::create('pisn_mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->string('id_registrasi_mahasiswa');
            $table->foreign('id_registrasi_mahasiswa')->references('id_registrasi_mahasiswa')->on('riwayat_pendidikans')->onDelete('cascade');
            $table->string('nim')->nullable();
            $table->string('id_semester')->nullable();
            $table->string('periode_wisuda')->nullable();
            $table->string('penomoran_ijazah_nasional')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pisn_mahasiswas');
    }
};
