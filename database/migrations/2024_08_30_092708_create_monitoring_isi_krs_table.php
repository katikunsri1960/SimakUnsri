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
        Schema::create('monitoring_isi_krs', function (Blueprint $table) {
            $table->id();
            $table->string('id_prodi')->unique();
            $table->integer('mahasiswa_aktif')->default(0);
            $table->integer('mahasiswa_aktif_min_7')->default(0);
            $table->integer('isi_krs')->default(0);
            $table->integer('krs_approved')->default(0);
            $table->integer('krs_not_approved')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitoring_isi_krs');
    }
};
