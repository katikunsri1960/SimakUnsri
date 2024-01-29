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
        Schema::create('rencana_evaluasis', function (Blueprint $table) {
            $table->id();
            $table->string("id_rencana_evaluasi")->nullable()->unique();
            $table->integer("id_jenis_evaluasi")->nullable();
            $table->foreign("id_jenis_evaluasi")->references("id_jenis_evaluasi")->on("jenis_evaluasis")->onDelete("cascade");
            $table->string("jenis_evaluasi")->nullable();
            $table->string("id_matkul")->nullable();
            $table->foreign("id_matkul")->references("id_matkul")->on("mata_kuliahs")->onDelete("cascade");
            $table->string("nama_mata_kuliah")->nullable();
            $table->string("kode_mata_kuliah")->nullable();
            $table->float("sks_mata_kuliah", 10, 2)->nullable();
            $table->string("id_prodi")->nullable();
            $table->foreign("id_prodi")->references("id_prodi")->on("program_studis")->onDelete("cascade");
            $table->string("nama_program_studi")->nullable();
            $table->string("nama_evaluasi")->nullable();
            $table->text("deskripsi_indonesia")->nullable();
            $table->text("deskrips_inggris")->nullable();
            $table->integer("nomor_urut")->nullable();
            $table->float("bobot_evaluasi", 10, 4)->nullable();
            $table->string("status_sync")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rencana_evaluasis');
    }
};
