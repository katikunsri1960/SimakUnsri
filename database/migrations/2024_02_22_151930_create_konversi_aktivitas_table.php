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
        Schema::create('konversi_aktivitas', function (Blueprint $table) {
            $table->id();
            $table->string('id_konversi_aktivitas')->unique();
            $table->string('id_matkul');
            $table->foreign('id_matkul')->references('id_matkul')->on('mata_kuliahs');
            $table->string('nama_mata_kuliah')->nullable();
            $table->string('id_aktivitas');
            $table->foreign('id_aktivitas')->references('id_aktivitas')->on('aktivitas_mahasiswas');
            $table->text('judul')->nullable();
            $table->string('id_anggota')->nullable();
            $table->string('nama_mahasiswa')->nullable();
            $table->string('nim')->nullable();
            $table->float('sks_mata_kuliah', 8, 2)->nullable();
            $table->float('nilai_angka')->nullable();
            $table->float('nilai_indeks')->nullable();
            $table->string('nilai_huruf')->nullable();
            $table->string('id_semester')->nullable();
            $table->foreign('id_semester')->references('id_semester')->on('semesters');
            $table->string('nama_semester')->nullable();
            $table->string('status_sync')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('konversi_aktivitas');
    }
};
