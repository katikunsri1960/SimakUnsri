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
        Schema::table('periode_wisudas', function (Blueprint $table) {
            $table->string('no_sk_skpi')->nullable()->after('tanggal_akhir_daftar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('periode_wisudas', function (Blueprint $table) {
            $table->dropColumn('no_sk_skpi');
        });
    }
};
