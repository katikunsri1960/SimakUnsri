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
        Schema::table('jenis_keluars', function (Blueprint $table) {
            $table->string('id_jenis_keluar')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jenis_keluars', function (Blueprint $table) {
            $table->string('id_jenis_keluar')->nullable(false)->change();
        });
    }
};
