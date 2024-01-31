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
        Schema::table('peserta_kelas_kuliahs', function (Blueprint $table) {
            // remove foreign key kelas_kuliahs from peserta_kelas_kuliahs id_kelas_kuliah
            $table->dropForeign(['id_kelas_kuliah']);
            $table->index('id_kelas_kuliah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peserta_kelas_kuliahs', function (Blueprint $table) {
            // add foreign key kelas_kuliahs from peserta_kelas_kuliahs id_kelas_kuliah
            $table->foreign('id_kelas_kuliah')->references('id_kelas_kuliah')->on('kelas_kuliahs');
            $table->dropIndex(['id_kelas_kuliah']);
        });
    }
};
