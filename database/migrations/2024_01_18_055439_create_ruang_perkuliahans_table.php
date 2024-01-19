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
        Schema::create('ruang_perkuliahans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_ruang');
            $table->string('lokasi');
            $table->string('id_prodi');
            $table->foreign('id_prodi')->references('id_prodi')->on('program_studis');
            $table->unique(['nama_ruang', 'lokasi', 'id_prodi']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ruang_perkuliahans');
    }
};
