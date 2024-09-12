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
        Schema::table('nilai_sidang_mahasiswa', function (Blueprint $table) {
            $table->string('id_dosen')->nullable()->after('id_aktivitas');
            $table->foreign('id_dosen')->references('id_dosen')->on('biodata_dosens')->onDelete('set null');
            $table->double('nilai_akhir_dosen')->nullable()->after('nilai_performansi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nilai_sidang_mahasiswa', function (Blueprint $table) {
            $table->dropForeign(['id_dosen']);
            $table->dropColumn('id_dosen');
            $table->dropColumn('nilai_akhir_dosen');
        });
    }
};
