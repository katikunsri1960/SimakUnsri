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
            $table->text('alasan_pembatalan')->after('approved')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cuti_kuliahs', function (Blueprint $table) {
            $table->dropColumn('alasan_pembatalan');
        });
    }
};
