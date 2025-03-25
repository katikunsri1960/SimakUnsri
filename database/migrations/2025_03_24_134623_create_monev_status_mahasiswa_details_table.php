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
        Schema::create('monev_status_mahasiswa_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('monev_status_mahasiswa_id')->constrained('monev_status_mahasiswas')->onDelete('cascade');
            $table->string('status');
            $table->string('id_registrasi_mahasiswa');
            $table->foreign('id_registrasi_mahasiswa')->references('id_registrasi_mahasiswa')->on('riwayat_pendidikans')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monev_status_mahasiswa_details');
    }
};
