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
        Schema::create('periode_perkuliahans', function (Blueprint $table) {
            $table->id();
            $table->string("id_prodi")->nullable();
            $table->foreign('id_prodi')->references('id_prodi')->on('program_studis')->onDelete('cascade');
            $table->string("nama_program_studi")->nullable();
            $table->string("id_semester")->nullable();
            $table->foreign('id_semester')->references('id_semester')->on('semesters')->onDelete('cascade');
            $table->unique(['id_prodi', 'id_semester'], 'unique_id_prodi_id_semester');
            $table->string("nama_semester")->nullable();
            $table->integer("jumlah_target_mahasiswa_baru")->nullable();
            $table->integer("jumlah_pendaftar_ikut_seleksi")->nullable();
            $table->integer("jumlah_pendaftar_lulus_seleksi")->nullable();
            $table->integer("jumlah_daftar_ulang")->nullable();
            $table->integer("jumlah_mengundurkan_diri")->nullable();
            $table->date("tanggal_awal_perkuliahan")->nullable();
            $table->date("tanggal_akhir_perkuliahan")->nullable();
            $table->integer("jumlah_minggu_pertemuan")->nullable();
            $table->string("status_sync")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periode_perkuliahans');
    }
};
