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
        Schema::table('uji_mahasiswas', function (Blueprint $table) {
            $table->string('status_uji_mahasiswa')->default('2')->after('penguji_ke')->comment('0:Belum di Setujui, 1:Sudah di Setujui Prodi, 2:Sudah di Setujui Dosen Penguji');
            
        });

        Schema::table('aktivitas_mahasiswas', function (Blueprint $table) {
            $table->boolean('approve_krs')->default('1')->after('id');
            $table->boolean('approve_sidang')->default('1')->after('approve_krs');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('uji_mahasiswas', function (Blueprint $table) {
            $table->dropColumn('status_uji_mahasiswa');
        });

        Schema::table('aktivitas_mahasiswas', function (Blueprint $table) {
            $table->dropColumn('approve_krs');
            $table->dropColumn('approve_sidang');
            
        });
    }
};
