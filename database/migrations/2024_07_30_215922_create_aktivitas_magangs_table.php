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
        Schema::create('aktivitas_magangs', function (Blueprint $table) {
            $table->id();
            $table->string("id_aktivitas")->nullable();
            $table->unique("id_aktivitas");
            $table->string("id_registrasi_mahasiswa");
            $table->foreign('id_registrasi_mahasiswa')->references('id_registrasi_mahasiswa')->on('riwayat_pendidikans');
            $table->string("nama_mahasiswa")->nullable();
            $table->string("id_semester")->nullable();
            $table->foreign('id_semester')->references('id_semester')->on('semesters')->onDelete('cascade');
            $table->string("nama_semester")->nullable();
            $table->string("nama_instansi")->nullable();
            $table->string("lokasi")->nullable();
            $table->boolean('approved')->default(0);
            $table->string("status_sync")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aktivitas_magangs');
    }
};
