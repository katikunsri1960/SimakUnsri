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
        Schema::create('rencana_pembelajarans', function (Blueprint $table) {
            $table->id();
            $table->string("id_rencana_ajar")->nullable()->unique();
            $table->string("id_matkul");
            $table->foreign("id_matkul")->references("id_matkul")->on("mata_kuliahs")->onDelete("cascade");
            $table->string("nama_mata_kuliah")->nullable();
            $table->string("kode_mata_kuliah")->nullable();
            $table->string("sks_mata_kuliah")->nullable();
            $table->string("id_prodi")->nullable();
            $table->foreign("id_prodi")->references("id_prodi")->on("program_studis")->onDelete("set null");
            $table->string("nama_program_studi")->nullable();
            $table->integer("pertemuan")->nullable();
            $table->text("materi_indonesia")->nullable();
            $table->text("materi_inggris")->nullable();
            $table->string("status_sync")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rencana_pembelajarans');
    }
};
