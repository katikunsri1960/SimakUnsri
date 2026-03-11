<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skpi_jenis_kegiatan', function (Blueprint $table) {
            $table->id();

            $table->foreignId('bidang_id')
                  ->constrained('skpi_bidang_kegiatan')
                  ->cascadeOnDelete();

            $table->string('nama_jenis');
            $table->text('kriteria')->nullable();
            $table->integer('skor')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skpi_jenis_kegiatan');
    }
};