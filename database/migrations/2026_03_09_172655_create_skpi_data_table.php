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
        Schema::create('skpi_data', function (Blueprint $table) {
            $table->id();
            $table->string('id_registrasi_mahasiswa');
            $table->string('nama_kegiatan');
            $table->unsignedBigInteger('id_jenis_skpi');
            $table->string('nama_jenis_skpi');
            $table->integer('skor')->default(0);
            $table->string('periode_wisuda')->nullable();
            $table->string('file_pendukung')->nullable();
            $table->integer('approved')->default('0');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('id_registrasi_mahasiswa')
                ->references('id_registrasi_mahasiswa')
                ->on('riwayat_pendidikans')
                ->onDelete('cascade');

            $table->foreign('id_jenis_skpi')
                ->references('id')
                ->on('skpi_jenis_kegiatan')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skpi_data');
    }
};
