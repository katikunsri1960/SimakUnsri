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
        Schema::table('asistensi_akhirs', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['id_aktivitas']);

            // Add the new foreign key constraint with onUpdate cascade
            $table->foreign('id_aktivitas')
                ->references('id_aktivitas')
                ->on('aktivitas_mahasiswas')
                ->onDelete('set null')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asistensi_akhirs', function (Blueprint $table) {
            $table->dropForeign(['id_aktivitas']);

            // Add the new foreign key constraint with onUpdate cascade
            $table->foreign('id_aktivitas')
                ->references('id_aktivitas')
                ->on('aktivitas_mahasiswas')
                ->onDelete('set null');
        });
    }
};
