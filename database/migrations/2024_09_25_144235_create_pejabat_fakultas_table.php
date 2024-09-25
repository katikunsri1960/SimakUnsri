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
            $table->string('id_registrasi_dosen')->nullable();
            $table->index('id_registrasi_dosen', 'idx_registrasi_dosen');
            $table->string('id_dosen')->nullable();
            $table->foreign('id_dosen')->references('id_dosen')->on('biodata_dosens')->onDelete('set null');
            $table->string('id_jabatan')->nullable();
            $table->string('nama_jabatan')->nullable();
            $table->string('nidn')->nullable();
            $table->string('nama_dosen')->nullable();
            $table->string('nip')->nullable();
            $table->string('gelar_depan');
            $table->string('gelar_belakang');
            $table->string('id_prodi')->nullable();
            $table->foreign('id_prodi')->references('id_prodi')->on('program_studis')->onDelete('set null');
            $table->unsignedBigInteger('id_fakultas')->nullable();
            $table->foreign('id_fakultas')->references('id')->on('fakultas')->onDelete('set null');
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
