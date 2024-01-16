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
        Schema::create('semesters', function (Blueprint $table) {
            $table->id();
            $table->string('id_semester')->unique();
            $table->index('id_semester', 'idx_semester');
            $table->year('id_tahun_ajaran');
            $table->index('id_tahun_ajaran', 'idx_tahun_ajaran');
            $table->string('nama_semester');
            $table->integer('semester');
            $table->boolean('a_periode_aktif');
            $table->string('tanggal_mulai')->nullable();
            $table->string('tanggal_selesai')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('semesters');
    }
};
