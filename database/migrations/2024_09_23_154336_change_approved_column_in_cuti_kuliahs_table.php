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
        Schema::table('cuti_kuliahs', function (Blueprint $table) {
            $table->dropColumn('approved');
        });
        Schema::table('cuti_kuliahs', function (Blueprint $table) {
            $table->integer('approved')->default(0)->after('file_pendukung')->comment('0: belum di setujui, 1: disetujui prodi, 2: disetujui fakultas, 3: disetujui univ, 9: ditolak');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cuti_kuliahs', function (Blueprint $table) {
            $table->dropColumn('approved');
            $table->boolean('approved')->default(0)->after('file_pendukung')->comment('0: belum di setujui, 1: disetujui prodi, 2: disetujui fakultas, 3: disetujui univ, 9: ditolak');
        });

        Schema::table('cuti_kuliahs', function (Blueprint $table) {
            $table->boolean('approved')->default(0)->after('file_pendukung')->comment('0: belum di setujui, 1: disetujui prodi, 2: disetujui fakultas, 3: disetujui univ, 9: ditolak');
        });
    }
};
