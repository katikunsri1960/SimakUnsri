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
        Schema::create('status_mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->string('id_status_mahasiswa')->unique();
            $table->index('id_status_mahasiswa', 'idx_status_mahasiswa');
            $table->string('nama_status_mahasiswa');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('status_mahasiswas');
    }
};
