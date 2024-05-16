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
        Schema::table('komponen_evaluasi_kelas', function (Blueprint $table) {
            $table->float('bobot_evaluasi', 5, 4)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('komponen_evaluasi_kelas', function (Blueprint $table) {
            $table->integer('bobot_evaluasi')->nullable()->change();
        });
    }
};
