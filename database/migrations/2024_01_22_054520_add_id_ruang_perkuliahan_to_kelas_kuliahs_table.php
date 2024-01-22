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
            $table->foreignId('ruang_perkuliahan_id')->nullable()->after('id')->constrained()->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelas_kuliahs', function (Blueprint $table) {
            $table->dropForeign(['ruang_perkuliahan_id']);
            $table->dropColumn('ruang_perkuliahan_id');
        });
    }
};
