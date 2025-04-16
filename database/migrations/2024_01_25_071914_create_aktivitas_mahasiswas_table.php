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
        Schema::create('aktivitas_mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->string('id_aktivitas')->nullable();
            $table->unique('id_aktivitas');
            $table->integer('program_mbkm')->nullable();
            $table->string('nama_program_mbkm')->nullable();
            $table->integer('jenis_anggota')->nullable();
            $table->string('nama_jenis_anggota')->nullable();
            $table->integer('id_jenis_aktivitas')->nullable();
            $table->foreign('id_jenis_aktivitas')->references('id_jenis_aktivitas_mahasiswa')->on('jenis_aktivitas_mahasiswas')->onDelete('cascade');
            $table->string('nama_jenis_aktivitas')->nullable();
            $table->string('id_prodi')->nullable();
            $table->foreign('id_prodi')->references('id_prodi')->on('program_studis')->onDelete('cascade');
            $table->string('nama_prodi')->nullable();
            $table->string('id_semester')->nullable();
            $table->foreign('id_semester')->references('id_semester')->on('semesters')->onDelete('cascade');
            $table->index(['id_semester', 'id_prodi'], 'idx_semester_prodi');
            $table->string('nama_semester')->nullable();
            $table->text('judul')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('lokasi')->nullable();
            $table->string('sk_tugas')->nullable();
            $table->date('tanggal_sk_tugas')->nullable();
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->boolean('untuk_kampus_merdeka')->nullable();
            $table->string('asal_data')->nullable();
            $table->string('nm_asaldata')->nullable();
            $table->string('status_sync')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aktivitas_mahasiswas');
    }
};
