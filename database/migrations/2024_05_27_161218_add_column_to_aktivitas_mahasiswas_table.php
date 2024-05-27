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
            $table->string('mk_konversi')->nullable()->after('status_sync');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('aktivitas_mahasiswas', function (Blueprint $table) {
            $table->dropColumn('mk_konversi');
        });
    }
};
