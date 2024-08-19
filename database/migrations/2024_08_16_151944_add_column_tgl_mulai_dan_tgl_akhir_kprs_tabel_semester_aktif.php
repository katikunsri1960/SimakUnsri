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
        Schema::table('semester_aktifs', function (Blueprint $table) {
            $table->date('tanggal_mulai_kprs')->after('batas_bayar_ukt')->nullable();
            $table->date('tanggal_akhir_kprs')->after('tanggal_mulai_kprs')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('semester_aktifs', function (Blueprint $table) {
            $table->dropColumn('tanggal_mulai_kprs');
            $table->dropColumn('tanggal_akhir_kprs');
        });
    }
};
