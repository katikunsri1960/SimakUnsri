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
        Schema::table('beasiswa_mahasiswas', function (Blueprint $table) {
            $table->text('link_sk')->nullable()->after('tanggal_akhir_beasiswa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('beasiswa_mahasiswas', function (Blueprint $table) {
            $table->dropColumn('link_sk');
        });
    }
};
