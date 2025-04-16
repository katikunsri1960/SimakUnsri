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
        Schema::create('substansi_kuliahs', function (Blueprint $table) {
            $table->id();
            $table->string('id_substansi')->nullable()->unique();
            $table->string('id_prodi')->nullable();
            $table->foreign('id_prodi')->references('id_prodi')->on('program_studis')->onDelete('set null');
            $table->string('nama_program_studi')->nullable();
            $table->string('nama_substansi')->nullable();
            $table->string('sks_mata_kuliah')->nullable();
            $table->string('sks_tatap_muka')->nullable();
            $table->string('sks_praktek')->nullable();
            $table->string('sks_praktek_lapangan')->nullable();
            $table->string('sks_simulasi')->nullable();
            $table->string('id_jenis_substansi')->nullable();
            $table->index('id_jenis_substansi', 'idx_jenis_substansi');
            $table->string('nama_jenis_substansi')->nullable();
            $table->string('tgl_create')->nullable();
            $table->string('last_update')->nullable();
            $table->string('status_sync')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('substansi_kuliahs');
    }
};
