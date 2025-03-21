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
        Schema::table('mata_kuliahs', function (Blueprint $table) {
            $table->string('nama_program_studi')->nullable()->after('id_prodi');
            // change tanggal akhir efektif to tanggal selesai efektif
            $table->dropColumn('tanggal_akhir_efektif');
            $table->date('tanggal_selesai_efektif')->nullable()->after('tanggal_mulai_efektif');
            // $table->unique('id_matkul');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mata_kuliahs', function (Blueprint $table) {
            $table->dropColumn('nama_program_studi');
            // change tanggal selesai efektif to tanggal akhir efektif
            $table->dropColumn('tanggal_selesai_efektif');
            $table->date('tanggal_akhir_efektif')->nullable()->after('tanggal_mulai_efektif');
            // $table->dropUnique('mata_kuliahs_id_matkul_unique');
        });
    }
};
