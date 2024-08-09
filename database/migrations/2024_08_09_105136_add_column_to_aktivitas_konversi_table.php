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
        Schema::table('aktivitas_konversi', function (Blueprint $table) {
            $table->string('sks_mata_kuliah')->nullable()->after('nama_mata_kuliah');
            $table->string('semester')->nullable()->after('sks_mata_kuliah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('aktivitas_konversi', function (Blueprint $table) {
            $table->dropColumn('sks_mata_kuliah');
            $table->dropColumn('semester');
        });
    }
};
