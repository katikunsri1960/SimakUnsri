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
        Schema::create('dosen_pengajar_kelas_kuliahs', function (Blueprint $table) {
            $table->id();
            $table->string("id_aktivitas_mengajar")->nullable()->unique();
            $table->string("id_registrasi_dosen")->nullable();
            $table->index('id_registrasi_dosen', 'idx_registrasi_dosen');
            $table->string("id_dosen")->nullable();
            $table->index('id_dosen', 'idx_dosen');
            $table->string("nidn")->nullable();
            $table->string("nama_dosen")->nullable();
            $table->string("id_kelas_kuliah")->nullable();
            $table->string("nama_kelas_kuliah")->nullable();
            $table->string("id_substansi")->nullable();
            $table->string("sks_substansi_total")->nullable();
            $table->string("rencana_minggu_pertemuan")->nullable();
            $table->string("realisasi_minggu_pertemuan")->nullable();
            $table->integer("id_jenis_evaluasi")->nullable();
            $table->foreign('id_jenis_evaluasi')->references('id_jenis_evaluasi')->on('jenis_evaluasis')->onDelete('set null');
            $table->string("nama_jenis_evaluasi")->nullable();
            $table->string("id_prodi")->nullable();
            $table->foreign('id_prodi')->references('id_prodi')->on('program_studis')->onDelete('set null');
            $table->string("id_semester")->nullable();
            $table->foreign('id_semester')->references('id_semester')->on('semesters')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosen_pengajar_kelas_kuliahs');
    }
};
