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
        Schema::create('mk_kelas', function (Blueprint $table) {
            $table->id();
            $table->string('kode_mata_kuliah');
            $table->string('nama_kelas_kuliah');
            $table->string('kelas_kuliah');
            $table->string('id_kelas_kuliah');
            $table->timestamps();
            $table->index('kode_mata_kuliah');
            $table->index('nama_kelas_kuliah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mk_kelas');
    }
};
