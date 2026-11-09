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
            // Menambahkan kolom nama_cabang setelah kolom nama_prestasi (opsional/nullable jika ada data lama)
            $table->string('cabang')->nullable()->after('nama_prestasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prestasi_mahasiswas', function (Blueprint $table) {
            $table->dropColumn('cabang');
        });
    }
};