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
        Schema::create('ikatan_kerjas', function (Blueprint $table) {
            $table->id();
            $table->string('id_ikatan_kerja')->nullable()->unique();
            $table->index('id_ikatan_kerja', 'idx_ikatan_kerja');
            $table->string('nama_ikatan_kerja')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ikatan_kerjas');
    }
};
