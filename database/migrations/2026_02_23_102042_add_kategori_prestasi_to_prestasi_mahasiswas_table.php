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
            $table->tinyInteger('kategori_prestasi')
                  ->default(2)
                  ->comment('1 = Pendanaan, 2 = Non Pendanaan')
                  ->after('nama_mahasiswa');
            $table->string('file_prestasi')->after('penyelenggara');
            $table->tinyInteger('approved')->default(0)->after('id_prestasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prestasi_mahasiswas', function (Blueprint $table) {
            $table->dropColumn('kategori_prestasi');
            $table->dropColumn('file_prestasi');
            $table->dropColumn('approved');
        });
    }
};