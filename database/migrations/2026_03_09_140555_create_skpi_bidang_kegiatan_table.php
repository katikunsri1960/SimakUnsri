<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skpi_bidang_kegiatan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bidang');
            $table->string('nama_kegiatan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skpi_bidang_kegiatan');
    }
};