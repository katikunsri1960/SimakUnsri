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
            $table->boolean('penilaian_langsung')->default('0')->after('semester');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('aktivitas_konversi', function (Blueprint $table) {
            $table->dropColumn('penilaian_langsung');
        });
    }
};
