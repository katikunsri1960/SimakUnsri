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
        Schema::create('aktivitas_konversi', function (Blueprint $table) {
            $table->id();
            $table->string('id_kurikulum');
            $table->foreign('id_kurikulum')->references('id_kurikulum')->on('list_kurikulums');
            $table->string('nama_kurikulum')->nullable();
            $table->string('id_prodi');
            $table->foreign('id_prodi')->references('id_prodi')->on('program_studis')->onDelete('cascade');
            $table->string('nama_program_studi')->nullable();
            $table->string('id_jenis_aktivitas');
            $table->string('nama_jenis_aktivitas');
            $table->string('id_matkul');
            $table->foreign('id_matkul')->references('id_matkul')->on('mata_kuliahs');
            $table->string('kode_mata_kuliah')->nullable();
            $table->string('nama_mata_kuliah')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aktivitas_konversi');
    }
};
