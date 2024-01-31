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
            // change nama_program_studi to nullable
            $table->string('nama_program_studi')->nullable()->change();
            $table->string('nama_semester')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelas_kuliahs', function (Blueprint $table) {
            // change nama_program_studi to nullable
            $table->string('nama_program_studi')->nullable(false)->change();
            $table->string('nama_semester')->nullable(false)->change();
        });
    }
};
