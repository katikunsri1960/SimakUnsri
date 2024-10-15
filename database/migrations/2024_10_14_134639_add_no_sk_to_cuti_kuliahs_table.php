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
        Schema::table('cuti_kuliahs', function (Blueprint $table) {
            $table->string('nim')->after('id_registrasi_mahasiswa');
            $table->string('no_sk')->nullable()->after('approved');
            $table->date('tanggal_sk')->nullable()->after('no_sk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cuti_kuliahs', function (Blueprint $table) {
            $table->dropColumn('nim');
            $table->dropColumn('no_sk');
            $table->dropColumn('tanggal_sk');
        });
    }
};
