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
        Schema::table('bimbing_mahasiswas', function (Blueprint $table) {
            $table->double('nilai_proses_bimbingan')->nullable()->after('pembimbing_ke');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bimbing_mahasiswas', function (Blueprint $table) {
            $table->dropColumn('nilai_proses_bimbingan');
        });
    }
};
