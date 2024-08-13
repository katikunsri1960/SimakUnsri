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
            $table->date('tanggal_approve')->after('approved')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peserta_kelas_kuliahs', function (Blueprint $table) {
            $table->dropColumn('tanggal_approve');
        });
    }
};
