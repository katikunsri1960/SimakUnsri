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
        Schema::create('nilai_komponen_evaluasis', function (Blueprint $table) {
            $table->id();
            $table->string('id_registrasi_mahasiswa');
            $table->string('id_komponen_evaluasi');
            $table->unique(['id_registrasi_mahasiswa', 'id_komponen_evaluasi'], 'unique_nilai_komponen');
            $table->float('nilai_komp_eval')->nullable();
            $table->string('id_prodi')->nullable();
            $table->string('nama_program_studi')->nullable();
            $table->string('id_periode')->nullable();
            $table->string('id_matkul')->nullable();
            $table->string('nama_mata_kuliah')->nullable();
            $table->string('id_kelas')->nullable();
            $table->string('nama_kelas_kuliah')->nullable();
            $table->float('sks_mata_kuliah')->nullable();
            $table->string('nim')->nullable();
            $table->string('nama_mahasiswa')->nullable();
            $table->integer('id_jns_eval')->nullable();
            $table->string('nama')->nullable();
            $table->string('nama_inggris')->nullable();
            $table->integer('urutan')->nullable();
            $table->decimal('bobot')->nullable();
            $table->string('angkatan')->nullable();
            $table->string('status_sync');
            $table->boolean('feeder')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_komponen_evaluasis');
    }
};
