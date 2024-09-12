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
        Schema::table('konversi_aktivitas', function (Blueprint $table) {
            $table->boolean('feeder')->after('id')->default('1');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('konversi_aktivitas', function (Blueprint $table) {
            $table->dropColumn('feeder');
        });
    }
};
