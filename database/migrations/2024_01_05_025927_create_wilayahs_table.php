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
        Schema::create('wilayahs', function (Blueprint $table) {
            $table->id();
            $table->string('id_wilayah');
            $table->index('id_wilayah', 'idx_wilayah');
            $table->integer('id_level_wilayah');
            $table->foreign('id_level_wilayah')->references('id_level_wilayah')->on('level_wilayahs');
            $table->string('id_negara');
            $table->index('id_negara', 'idx_negara');
            $table->string('nama_wilayah');
            $table->string('id_induk_wilayah')->nullable();
            $table->index('id_induk_wilayah', 'idx_induk_wilayah');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wilayahs');
    }
};
