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
            $table->foreign('id_matkul')->references('id_matkul')->on('mata_kuliahs')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peserta_kelas_kuliahs', function (Blueprint $table) {
            $table->dropForeign(['id_matkul']);
        });
    }
};
