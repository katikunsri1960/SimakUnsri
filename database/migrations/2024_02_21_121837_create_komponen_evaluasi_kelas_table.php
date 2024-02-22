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
        Schema::create('komponen_evaluasi_kelas', function (Blueprint $table) {
            $table->id();
            $table->string("id_komponen_evaluasi")->nullable();
            $table->string("id_kelas_kuliah");
            $table->foreign('id_kelas_kuliah')->references('id_kelas_kuliah')->on('kelas_kuliahs');
            $table->integer("id_jenis_evaluasi");
            $table->foreign("id_jenis_evaluasi")->references("id_jenis_evaluasi")->on("jenis_evaluasis");
            $table->string("nama")->nullable();
            $table->string("nama_inggris")->nullable();
            $table->integer("nomor_urut")->nullable();
            $table->integer("bobot_evaluasi")->nullable();
            $table->string("last_update")->nullable();
            $table->string("tgl_create")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('komponen_evaluasi_kelas');
    }
};
