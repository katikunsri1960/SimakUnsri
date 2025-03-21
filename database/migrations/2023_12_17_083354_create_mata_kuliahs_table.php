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
        Schema::create('mata_kuliahs', function (Blueprint $table) {
            $table->id();
            $table->string('id_matkul')->unique();
            // $table->index('id_matkul');
            $table->string('kode_mata_kuliah');
            $table->string('nama_mata_kuliah');
            $table->string('nama_mata_kuliah_english')->nullable();
            $table->string('id_prodi');
            $table->index('id_prodi');
            $table->string('id_jenis_mata_kuliah')->nullable();
            $table->string('id_kelompok_mata_kuliah')->nullable();
            $table->float('sks_mata_kuliah');
            $table->float('sks_tatap_muka')->nullable();
            $table->float('sks_praktek')->nullable();
            $table->float('sks_praktek_lapangan')->nullable();
            $table->float('sks_simulasi')->nullable();
            $table->string('metode_kuliah')->nullable();
            $table->boolean('ada_sap')->nullable();
            $table->boolean('ada_silabus')->nullable();
            $table->boolean('ada_bahan_ajar')->nullable();
            $table->boolean('ada_acara_praktek')->nullable();
            $table->boolean('ada_diktat')->nullable();
            $table->date('tanggal_mulai_efektif')->nullable();
            $table->date('tanggal_akhir_efektif')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mata_kuliahs');
    }
};
