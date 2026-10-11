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
            // Menambahkan kolom alasan_pembatalan bertipe text setelah kolom approved
            $table->text('alasan_pembatalan')->nullable()->after('id_aktivitas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prestasi_mahasiswas', function (Blueprint $table) {
            $table->dropColumn('alasan_pembatalan');
        });
    }
};