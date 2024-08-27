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
            $table->date('tanggal_pembayaran')->after('status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayaran_manual_mahasiswas', function (Blueprint $table) {
            $table->dropColumn('tanggal_pembayaran');
        });
    }
};
