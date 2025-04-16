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
        Schema::create('prestasi_mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->string('id_prestasi')->nullable()->unique();
            $table->string('id_mahasiswa')->nullable();
            $table->foreign('id_mahasiswa')->references('id_mahasiswa')->on('biodata_mahasiswas');
            $table->string('nama_mahasiswa')->nullable();
            $table->integer('id_jenis_prestasi')->nullable();
            $table->foreign('id_jenis_prestasi')->references('id_jenis_prestasi')->on('jenis_prestasis');
            $table->string('nama_jenis_prestasi')->nullable();
            $table->integer('id_tingkat_prestasi')->nullable();
            $table->foreign('id_tingkat_prestasi')->references('id_tingkat_prestasi')->on('tingkat_prestasis');
            $table->string('nama_tingkat_prestasi')->nullable();
            $table->string('nama_prestasi')->nullable();
            $table->string('tahun_prestasi')->nullable();
            $table->string('penyelenggara')->nullable();
            $table->string('peringkat')->nullable();
            $table->string('id_aktivitas')->nullable();
            $table->string('status_sync')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestasi_mahasiswas');
    }
};
