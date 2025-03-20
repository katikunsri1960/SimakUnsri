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
            $table->string('alamat_orang_tua')->nullable()->after('nama_mahasiswa');
            $table->string('ipk')->nullable()->after('sks_diakui');
            $table->date('tgl_sk_pembimbing')->nullable()->after('kosentrasi');
            $table->string('no_sk_pembimbing')->nullable()->after('tgl_sk_pembimbing');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_wisuda', function (Blueprint $table) {
            $table->dropColumn('alamat_orang_tua');
            $table->dropColumn('ipk');
        });
    }
};
