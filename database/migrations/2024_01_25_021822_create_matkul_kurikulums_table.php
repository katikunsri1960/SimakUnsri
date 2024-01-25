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
        Schema::dropIfExists('matkul_kurikulums');

        Schema::create('matkul_kurikulums', function (Blueprint $table) {
            $table->id();
            $table->string("tgl_create")->nullable();
            $table->string("id_kurikulum");
            $table->foreign('id_kurikulum')->references('id_kurikulum')->on('list_kurikulums')->onDelete('cascade');
            $table->string("nama_kurikulum")->nullable();
            $table->string("id_matkul")->nullable();
            $table->foreign('id_matkul')->references('id_matkul')->on('mata_kuliahs')->onDelete('cascade');
            $table->unique(['id_kurikulum', 'id_matkul'], 'unique_matkul_kurikulum');
            $table->string("kode_mata_kuliah")->nullable();
            $table->string("nama_mata_kuliah")->nullable();
            $table->string("id_prodi")->nullable();
            $table->foreign('id_prodi')->references('id_prodi')->on('program_studis')->onDelete('cascade');
            $table->string("nama_program_studi")->nullable();
            $table->string("semester")->nullable();
            $table->string("id_semester")->nullable();
            $table->foreign('id_semester')->references('id_semester')->on('semesters')->onDelete('cascade');
            $table->index(['id_prodi', 'id_semester'], 'idx_prodi_semester');
            $table->string("semester_mulai_berlaku")->nullable();
            $table->float("sks_mata_kuliah", 2)->nullable();
            $table->float("sks_tatap_muka", 2)->nullable();
            $table->float("sks_praktek",2)->nullable();
            $table->float("sks_praktek_lapangan", 2)->nullable();
            $table->float("sks_simulasi", 2)->nullable();
            $table->boolean("apakah_wajib")->nullable();
            $table->string("status_sync")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matkul_kurikulums');
    }
};
