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
        Schema::table('data_wisuda', function (Blueprint $table) {
            $table->unique('id_registrasi_mahasiswa');
            $table->integer('approved')->comment('Belum Diapproved = 0, Approved Prodi = 1, Approved Fakultas = 2, Approved BAK = 3, Ditolak Prodi = 97, Ditolak Fakultas = 98, Ditolak BAK = 99')->change();
            $table->string('no_sk_yudisium')->nullable()->after('wisuda_ke');
            $table->date('tgl_sk_yudisium')->nullable()->after('no_sk_yudisium');
            $table->string('alasan_pembatalan')->nullable()->after('approved');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_wisuda', function (Blueprint $table) {
            $table->integer('approved')->change();
            $table->dropColumn('no_sk_yudisium');
            $table->dropColumn('tgl_sk_yudisium');
            $table->dropColumn('alasan_pembatalan');
        });
    }
};
