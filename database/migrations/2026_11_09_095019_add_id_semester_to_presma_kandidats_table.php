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
        Schema::table('presma_kandidats', function (Blueprint $table) {
            // Menambahkan kolom id_semester setelah kolom no_urut
            $table->string('id_semester')->after('no_urut')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presma_kandidats', function (Blueprint $table) {
            $table->dropColumn('id_semester');
        });
    }
};