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
        Schema::create('nilai_sidang_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->string('approved_prodi')->default(0);
            $table->string('id_aktivitas')->nullable();
            $table->foreign('id_aktivitas')->references('id_aktivitas')->on('aktivitas_mahasiswas')->onDelete('set null');
            $table->double('nilai_kualitas_skripsi');
            $table->double('nilai_presentasi_dan_diskusi');
            $table->double('nilai_performansi');
            $table->date('tanggal_penilaian_sidang');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_sidang_mahasiswa');
    }
};
