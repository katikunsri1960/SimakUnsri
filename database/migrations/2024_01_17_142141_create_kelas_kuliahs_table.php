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
        Schema::create('kelas_kuliahs', function (Blueprint $table) {
            $table->id();
            $table->string("id_kelas_kuliah")->nullable()->unique();
            $table->string("id_prodi")->nullable();
            $table->foreign('id_prodi')->references('id_prodi')->on('program_studis')->nullOnDelete();
            $table->string("nama_program_studi");
            $table->string("id_semester")->nullable();
            $table->foreign('id_semester')->references('id_semester')->on('semesters')->nullOnDelete();
            $table->string("nama_semester");
            $table->string("id_matkul")->nullable();
            $table->foreign('id_matkul')->references('id_matkul')->on('mata_kuliahs')->nullOnDelete();
            $table->string("kode_mata_kuliah")->nullable();
            $table->string("nama_mata_kuliah")->nullable();
            $table->string("nama_kelas_kuliah");
            $table->string("bahasan")->nullable();
            $table->date("tanggal_mulai_efektif")->nullable();
            $table->date("tanggal_akhir_efektif")->nullable();
            $table->integer("kapasitas")->nullable();
            $table->string("tanggal_tutup_daftar")->nullable();
            $table->string("prodi_penyelenggara")->nullable();
            $table->string("perguruan_tinggi_penyelenggara")->nullable();
            $table->string('mode')->nullable();
            $table->integer('lingkup')->nullable();
            $table->boolean('apa_untuk_pditt')->default(false);
            $table->string('jadwal_hari')->nullable();
            $table->time('jadwal_jam_mulai')->nullable();
            $table->time('jadwal_jam_selesai')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas_kuliahs');
    }
};
