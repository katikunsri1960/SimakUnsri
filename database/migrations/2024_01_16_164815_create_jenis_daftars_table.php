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
        Schema::create('jenis_daftars', function (Blueprint $table) {
            $table->id();
            $table->integer('id_jenis_daftar')->unique();
            $table->string('nama_jenis_daftar');
            $table->boolean('untuk_daftar_sekolah');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_daftars');
    }
};
