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
            $table->string('status_uji_mahasiswa')->after('penguji_ke')->comment('0:Belum di Setujui, 1:Sudah di Setujui Prodi, 2:Sudah di Setujui Dosen Penguji, 3:Dibatalkan Oleh Dosen Penguji')->change();

        });

        Schema::table('aktivitas_mahasiswas', function (Blueprint $table) {
            $table->date('jadwal_ujian')->nullable()->after('sks_aktivitas');
            $table->time('jadwal_jam_mulai')->nullable()->after('jadwal_ujian');
            $table->time('jadwal_jam_selesai')->nullable()->after('jadwal_ujian');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('uji_mahasiswas', function (Blueprint $table) {
            $table->string('status_uji_mahasiswa')->after('penguji_ke')->comment('0:Belum di Setujui, 1:Sudah di Setujui Prodi, 2:Sudah di Setujui Dosen Penguji')->change();
        });

        Schema::table('aktivitas_mahasiswas', function (Blueprint $table) {
            $table->dropColumn('jadwal_ujian');
            $table->dropColumn('jadwal_jam_mulai');
            $table->dropColumn('jadwal_jam_selesai');
        });
    }
};
