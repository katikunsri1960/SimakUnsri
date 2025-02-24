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
        Schema::table('data_wisuda', function (Blueprint $table) {
            $table->integer('lama_studi')->nullable()->after('tgl_keluar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_wisuda', function (Blueprint $table) {
            $table->dropColumn('lama_studi');
        });
    }
};
