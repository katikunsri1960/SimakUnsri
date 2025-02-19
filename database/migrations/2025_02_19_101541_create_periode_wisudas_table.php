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
        Schema::create('periode_wisudas', function (Blueprint $table) {
            $table->id();
            $table->integer('periode');
            $table->date('tanggal_wisuda');
            $table->date('tanggal_mulai_daftar');
            $table->date('tanggal_akhir_daftar');
            $table->boolean('is_active')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periode_wisudas');
    }
};
