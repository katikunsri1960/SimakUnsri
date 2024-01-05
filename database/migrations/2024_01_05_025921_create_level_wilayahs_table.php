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
        Schema::create('level_wilayahs', function (Blueprint $table) {
            $table->id();
            $table->integer('id_level_wilayah');
            $table->index('id_level_wilayah', 'idx_level_wilayah');
            $table->string('nama_level_wilayah');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('level_wilayahs');
    }
};
