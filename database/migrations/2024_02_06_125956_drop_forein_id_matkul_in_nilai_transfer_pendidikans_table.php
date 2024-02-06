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
        Schema::table('nilai_transfer_pendidikans', function (Blueprint $table) {
            // $table->dropForeign(['id_matkul']);
            // drop constrined id_matkul
            $table->dropForeign('nilai_transfer_pendidikans_id_matkul_foreign');
            $table->index('id_matkul', 'idx_matkul');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nilai_transfer_pendidikans', function (Blueprint $table) {
            $table->dropIndex('idx_matkul');
            $table->foreign('id_matkul')->references('id_matkul')->on('mata_kuliahs');

        });
    }
};
