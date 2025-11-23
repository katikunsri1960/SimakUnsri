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
        Schema::create('kehadiran_dosen', function (Blueprint $table) {
            $table->id();
            $table->string('kode_mata_kuliah')->nullable();
            $table->string('nama_kelas')->nullable();
            $table->string('id_kelas_kuliah')->nullable();
            $table->string('nama_mk')->nullable();
            $table->integer('session_id');
            $table->string('session_date')->nullable();
            $table->string('deskripsi_sesi')->nullable();
            $table->integer('id_kehadiran')->nullable();
            $table->integer('timemodified')->nullable();
            $table->string('lasttaken')->nullable();
            $table->timestamps();

             // Membuat session_id unik
            $table->unique('session_id');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
