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
        Schema::table('prestasi_mahasiswas', function (Blueprint $table) {
            // Kolom tracking sync Feeder PDDikti (0: Belum Sync, 1: Sudah Sync/Ada di Feeder)
            $table->boolean('feeder')->default(0)->after('alasan_pembatalan');
            $table->string('file_prestasi')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prestasi_mahasiswas', function (Blueprint $table) {
            $table->dropColumn('feeder');
            $table->string('file_prestasi')->nullable(false)->change();
        });
    }
};