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
        Schema::create('presma_kandidats', function (Blueprint $table) {
            $table->id();
            $table->integer('no_urut');
            $table->string('id_registrasi_mahasiswa');
            $table->string('nim');
            $table->string('nama_mahasiswa');
            $table->enum('posisi', ['capresma', 'cawapresma']);
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('id_registrasi_mahasiswa')
                  ->references('id_registrasi_mahasiswa')
                  ->on('riwayat_pendidikans')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presma_kandidats');
    }
};