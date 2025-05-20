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
            $table->unsignedBigInteger('id_file_fakultas')->nullable()->after('id_prodi');
            $table->foreign('id_file_fakultas')->references('id')->on('file_fakultas')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_wisuda', function (Blueprint $table) {
            $table->dropForeign(['id_file_fakultas']);
            $table->dropColumn('id_file_fakultas');
        });
    }
};
