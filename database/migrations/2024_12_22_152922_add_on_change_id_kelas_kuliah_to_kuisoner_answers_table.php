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
        Schema::table('kuisoner_answers', function (Blueprint $table) {
            $table->dropForeign(['id_kelas_kuliah']);

            $table->foreign('id_kelas_kuliah')
                ->references('id_kelas_kuliah')
                ->on('kelas_kuliahs')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kuisoner_answers', function (Blueprint $table) {
            $table->dropForeign(['id_kelas_kuliah']);

            $table->foreign('id_kelas_kuliah')
                ->references('id_kelas_kuliah')
                ->on('kelas_kuliahs')
                ->onDelete('cascade');
        });
    }
};
