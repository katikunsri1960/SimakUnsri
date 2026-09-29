<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skpi_sub_bidang_kegiatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bidang_id')
                  ->constrained('skpi_bidang_kegiatan')
                  ->onDelete('cascade');
            $table->string('nama_sub_bidang');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skpi_sub_bidang_kegiatan');
    }
};