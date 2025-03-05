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
        Schema::table('aktivitas_kuliah_mahasiswas', function (Blueprint $table) {
            $table->index(['feeder', 'id_prodi', 'id_semester'], 'akm_feeder_prodi_semester');
            $table->index(['id_prodi', 'id_semester'], 'akm_prodi_semester');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('aktivitas_kuliah_mahasiswas', function (Blueprint $table) {
            $table->dropIndex('akm_feeder_prodi_semester');
            $table->dropIndex('akm_prodi_semester');
        });
    }
};
