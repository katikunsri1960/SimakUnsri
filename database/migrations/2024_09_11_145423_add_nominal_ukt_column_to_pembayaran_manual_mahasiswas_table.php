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
        Schema::table('pembayaran_manual_mahasiswas', function (Blueprint $table) {
            $table->bigInteger('nominal_ukt')->nullable()->after('tanggal_pembayaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayaran_manual_mahasiswas', function (Blueprint $table) {
            $table->dropColumn('nominal_ukt');
        });
    }
};
