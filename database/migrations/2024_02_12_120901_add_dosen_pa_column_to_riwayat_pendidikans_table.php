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
            $table->string('dosen_pa')->nullable()->after('status_sync');
            $table->foreign('dosen_pa')->references('id_dosen')->on('biodata_dosens');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('riwayat_pendidikans', function (Blueprint $table) {
            $table->dropForeign('riwayat_pendidikans_dosen_pa_foreign');
            $table->dropColumn('dosen_pa');
        });
    }
};
