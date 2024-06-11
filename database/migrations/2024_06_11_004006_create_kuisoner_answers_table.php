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
        Schema::create('kuisoner_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kuisoner_question_id')->constrained('kuisoner_questions')->onDelete('cascade');
            $table->string('id_kelas_kuliah')->nullable();
            $table->foreign('id_kelas_kuliah')->references('id_kelas_kuliah')->on('kelas_kuliahs')->onDelete('set null');
            $table->string('id_registrasi_mahasiswa')->nullable();
            $table->foreign('id_registrasi_mahasiswa')->references('id_registrasi_mahasiswa')->on('riwayat_pendidikans')->onDelete('set null');
            $table->integer('nilai');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kuisoner_answers');
    }
};
