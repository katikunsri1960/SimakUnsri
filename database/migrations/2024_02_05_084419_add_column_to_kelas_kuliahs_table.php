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
        Schema::table('kelas_kuliahs', function (Blueprint $table) {
            $table->boolean('feeder')->default(true)->after('ruang_perkuliahan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelas_kuliahs', function (Blueprint $table) {
            $table->dropColumn('feeder');
        });
    }
};
