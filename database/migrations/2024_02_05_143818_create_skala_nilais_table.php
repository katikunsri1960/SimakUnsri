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
        Schema::create('skala_nilais', function (Blueprint $table) {
            $table->id();
            $table->string("id_bobot_nilai")->nullable()->unique();
            $table->string("id_prodi")->nullable();
            $table->foreign('id_prodi')->references('id_prodi')->on('program_studis')->onDelete('cascade');
            $table->string("nama_program_studi")->nullable();
            $table->string("nilai_huruf")->nullable();
            $table->float("nilai_indeks", 8, 2)->nullable();
            $table->float("bobot_minimum", 8, 2)->nullable();
            $table->float("bobot_maksimum", 8, 2)->nullable();
            $table->date("tanggal_mulai_efektif")->nullable();
            $table->date("tanggal_akhir_efektif")->nullable();
            $table->string("tgl_create")->nullable();
            $table->string("status_sync")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skala_nilais');
    }
};
