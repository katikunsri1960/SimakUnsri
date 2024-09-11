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
        Schema::create('notulensi_sidang_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->string('id_aktivitas')->nullable();
            $table->foreign('id_aktivitas')->references('id_aktivitas')->on('aktivitas_mahasiswas')->onDelete('set null');
            $table->string('id_dosen')->nullable();
            $table->foreign('id_dosen')->references('id_dosen')->on('biodata_dosens')->onDelete('set null');
            $table->string('lokasi');
            $table->date('tanggal_sidang');
            $table->time('jam_mulai_sidang');
            $table->time('jam_selesai_sidang');
            $table->time('jam_mulai_presentasi');
            $table->time('jam_selesai_presentasi');
            $table->text('uraian');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notulensi_sidang_mahasiswa');
    }
};
