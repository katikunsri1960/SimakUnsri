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
            $table->string('id_prodi')->nullable()->after('nama_semester');
            // $table->foreign('id_prodi')->references('id_prodi')->on('riwayat_pendidikans')->onDelete('set null');
            $table->string('alamat')->nullable()->after('id_prodi');
            $table->string('handphone')->nullable()->after('alamat');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cuti_kuliahs', function (Blueprint $table) {
            // $table->dropForeign(['id_prodi']);
            $table->dropColumn('id_prodi');
            $table->dropColumn('alamat');
            $table->dropColumn('handphone');
        });
    }
};
