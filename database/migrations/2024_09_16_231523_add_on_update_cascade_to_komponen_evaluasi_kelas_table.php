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
            Schema::table('komponen_evaluasi_kelas', function (Blueprint $table) {
                // Drop foreign key constraint yang lama
                $table->dropForeign(['id_kelas_kuliah']);
                $table->dropForeign(['id_jenis_evaluasi']);

                // Tambahkan foreign key baru dengan opsi onUpdate('cascade')
                $table->foreign('id_kelas_kuliah')
                      ->references('id_kelas_kuliah')
                      ->on('kelas_kuliahs')
                      ->onUpdate('cascade'); // Tambahkan opsi onUpdate cascade

                $table->foreign('id_jenis_evaluasi')
                      ->references('id_jenis_evaluasi')
                      ->on('jenis_evaluasis')
                      ->onUpdate('cascade'); // Tambahkan opsi onUpdate cascade
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('komponen_evaluasi_kelas', function (Blueprint $table) {
            $table->dropForeign(['id_kelas_kuliah']);
            $table->dropForeign(['id_jenis_evaluasi']);

            // Tambahkan foreign key lama tanpa opsi onUpdate cascade
            $table->foreign('id_kelas_kuliah')
                  ->references('id_kelas_kuliah')
                  ->on('kelas_kuliahs');

            $table->foreign('id_jenis_evaluasi')
                  ->references('id_jenis_evaluasi')
                  ->on('jenis_evaluasis');
        });
    }
};
