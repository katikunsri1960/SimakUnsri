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
        Schema::create('pembayaran_manual_mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->string('id_registrasi_mahasiswa')->nullable();
            $table->foreign('id_registrasi_mahasiswa')->references('id_registrasi_mahasiswa')->on('riwayat_pendidikans')->onDelete('set null');
            $table->string('nim');
            $table->string('id_semester')->nullable();
            $table->foreign('id_semester')->references('id_semester')->on('semesters')->onDelete('set null');
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran_manual_mahasiswas');
    }
};
