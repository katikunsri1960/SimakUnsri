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
        Schema::table('asistensi_akhirs', function (Blueprint $table) {
            $table->string('id_dosen')->nullable()->after('id_aktivitas');
            $table->foreign('id_dosen')->references('id_dosen')->on('biodata_dosens')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asistensi_akhirs', function (Blueprint $table) {
            $table->dropForeign(['id_dosen']);
            $table->dropColumn('id_dosen');
        });
    }
};
