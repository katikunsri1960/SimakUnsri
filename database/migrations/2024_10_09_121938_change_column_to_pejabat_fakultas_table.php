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
        Schema::table('pejabat_fakultas', function (Blueprint $table) {
            // Hapus unique constraint lama (hanya pada 'id_jabatan')
            $table->dropUnique(['id_jabatan']);
            
            // Tambahkan unique constraint baru pada kombinasi 'id_jabatan' dan 'id_fakultas'
            $table->unique(['id_jabatan', 'id_fakultas'], 'unique_jabatan_fakultas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pejabat_fakultas', function (Blueprint $table) {
            // Hapus unique constraint yang baru ditambahkan
            $table->dropUnique('unique_jabatan_fakultas');

            // Tambahkan kembali unique constraint pada 'id_jabatan'
            $table->unique('id_jabatan');
        });
    }
};
