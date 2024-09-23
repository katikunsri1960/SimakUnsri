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
        Schema::create('all_pts', function (Blueprint $table) {
            $table->id();
            $table->string('id_perguruan_tinggi')->unique();
            $table->string('kode_perguruan_tinggi')->nullable();
            $table->text('nama_perguruan_tinggi')->nullable();
            $table->string('nama_singkat')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('all_pts');
    }
};
