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
        Schema::table('riwayat_pendidikans', function (Blueprint $table) {
            $table->string('sks_maks_pmm')->nullable()->after('id_kurikulum');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('riwayat_pendidikans', function (Blueprint $table) {
            $table->dropColumn('sks_maks_pmm');
        });
    }
};
