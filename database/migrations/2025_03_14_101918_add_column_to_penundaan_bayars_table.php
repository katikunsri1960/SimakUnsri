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
        Schema::table('penundaan_bayars', function (Blueprint $table) {
            $table->string('file_pendukung')->nullable()->after('keterangan');
            $table->string('alasan_pembatalan')->nullable()->after('status');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penundaan_bayars', function (Blueprint $table) {
            $table->dropColumn('file_pendukung');
            $table->dropColumn('alasan_pembatalan');
        });
    }
};
