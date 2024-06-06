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
        Schema::create('asistensi_akhirs', function (Blueprint $table) {
            $table->id();
            $table->string('id_aktivitas')->nullable();
            $table->foreign('id_aktivitas')->references('id_aktivitas')->on('aktivitas_mahasiswas')->onDelete('set null');
            $table->boolean('approved')->default(0);
            $table->date('tanggal');
            $table->text('uraian');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asistensi_akhirs');
    }
};
