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
        Schema::table('aktivitas_mahasiswas', function (Blueprint $table) {
            $table->string('alasan_pembatalan_sidang')->nullable()->after('approve_sidang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('aktivitas_mahasiswas', function (Blueprint $table) {
            $table->dropColumn('alasan_pembatalan_sidang');
        });
    }
};
