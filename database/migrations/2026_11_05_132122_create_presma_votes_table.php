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
        Schema::create('presma_votes', function (Blueprint $table) {
            $table->id();
            $table->string('id_semester');
            $table->string('id_registrasi_mahasiswa'); // Kolom Foreign Key
            $table->integer('no_urut');
            $table->timestamps();

            // Foreign key constraint ke tabel riwayat_pendidikans
            $table->foreign('id_registrasi_mahasiswa')
                  ->references('id_registrasi_mahasiswa')
                  ->on('riwayat_pendidikans')
                  ->onDelete('cascade');

            // Unique constraint: 1 mahasiswa hanya bisa memilih 1 kali per semester
            $table->unique(['id_registrasi_mahasiswa', 'id_semester']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presma_votes');
    }
};