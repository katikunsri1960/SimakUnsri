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
            $table->date('tgl_mulai_pengajuan_cuti')->after('tanggal_akhir_kprs')->nullable();
            $table->date('tgl_selesai_pengajuan_cuti')->after('tgl_mulai_pengajuan_cuti')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('semester_aktifs', function (Blueprint $table) {
            $table->dropColumn('tgl_mulai_pengajuan_cuti');
            $table->dropColumn('tgl_selesai_pengajuan_cuti');
        });
    }
};
