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
        Schema::table('batas_isi_krs_manual', function (Blueprint $table) {
            $table->string('id_semester')->nullable()->after('nama_mahasiswa');
            $table->foreign('id_semester')->references('id_semester')->on('semesters')->onDelete('set null');
            $table->unique(['id_registrasi_mahasiswa', 'id_semester'], 'unique_mahasiswa_semester');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('batas_isi_krs_manual', function (Blueprint $table) {
            $table->dropForeign(['id_semester']);
            $table->dropColumn('id_semester');
            $table->dropUnique('unique_mahasiswa_semester');
        });
    }
};
