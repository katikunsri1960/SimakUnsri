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
            // Tambahkan unique constraint pada kolom id_registrasi_mahasiswa dan id_semester
            $table->unique(['id_registrasi_mahasiswa', 'id_semester'], 'unique_mahasiswa_semester');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cuti_kuliahs', function (Blueprint $table) {
            // Hapus unique constraint jika terjadi rollback
            $table->dropUnique('unique_mahasiswa_semester');
        });
    }
};
