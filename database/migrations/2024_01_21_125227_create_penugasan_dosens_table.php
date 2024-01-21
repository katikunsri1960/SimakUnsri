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
        Schema::create('penugasan_dosens', function (Blueprint $table) {
            $table->id();
            $table->string("id_registrasi_dosen")->nullable();
            $table->enum("jk", ['L', 'P'])->nullable();
            $table->string("id_dosen")->nullable();
            $table->foreign('id_dosen')->references('id_dosen')->on('biodata_dosens')->onDelete('set null');
            $table->string("nama_dosen")->nullable();
            $table->string("nidn")->nullable();
            $table->string("id_tahun_ajaran")->nullable();
            $table->unique(['id_tahun_ajaran', 'id_registrasi_dosen']);
            $table->string("nama_tahun_ajaran")->nullable();
            $table->string("id_perguruan_tinggi")->nullable();
            $table->string("nama_perguruan_tinggi")->nullable();
            $table->string("id_prodi")->nullable();
            $table->index(['id_prodi', 'id_tahun_ajaran', 'id_dosen'], 'idx_prodi_tahun_ajaran_dosen');
            $table->string("nama_program_studi")->nullable();
            $table->string("nomor_surat_tugas")->nullable();
            $table->string("tanggal_surat_tugas")->nullable();
            $table->string("mulai_surat_tugas")->nullable();
            $table->string("tgl_create")->nullable();
            $table->string("tgl_ptk_keluar")->nullable();
            $table->string("id_stat_pegawai")->nullable();
            $table->string("id_jns_keluar")->nullable();
            $table->string("id_ikatan_kerja")->nullable();
            $table->string("a_sp_homebase")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penugasan_dosens');
    }
};
