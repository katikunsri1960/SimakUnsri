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
        Schema::create('pejabat_fakultas', function (Blueprint $table) {
            $table->id();
            $table->string('id_registrasi_dosen');
            $table->index('id_registrasi_dosen', 'idx_registrasi_dosen');
            $table->string('id_dosen');
            $table->foreign('id_dosen')->references('id_dosen')->on('biodata_dosens')->onDelete('cascade');
            $table->string('id_jabatan')->unique();
            $table->string('nama_jabatan');
            $table->string('nidn')->nullable();
            $table->string('nama_dosen')->nullable();
            $table->string('nip')->nullable();
            $table->string('gelar_depan')->nullable();
            $table->string('gelar_belakang')->nullable();
            $table->unsignedBigInteger('id_fakultas');
            $table->foreign('id_fakultas')->references('id')->on('fakultas')->unique()->onDelete('cascade');
            $table->string('nama_fakultas')->nullable();
            $table->date('tgl_mulai_jabatan');
            $table->date('tgl_selesai_jabatan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pejabat_fakultas');
    }
};
