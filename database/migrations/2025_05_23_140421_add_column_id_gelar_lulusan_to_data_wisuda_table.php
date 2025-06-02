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
            $table->unsignedBigInteger('id_gelar_lulusan')->nullable()->after('id_file_fakultas');
            $table->foreign('id_gelar_lulusan')->references('id')->on('gelar_lulusans')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_wisuda', function (Blueprint $table) {
            $table->dropForeign(['id_gelar_lulusan']);
            $table->dropColumn('id_gelar_lulusan');
        });
    }
};
