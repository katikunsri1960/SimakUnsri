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
        Schema::table('skpi_data', function (Blueprint $table) {
            // 1. Tambahkan kolom id_prestasi (nullable agar data lama tidak error)
            // Menggunakan type char/string/uuid atau unsignedBigInteger menyesuaikan tipe id_prestasi
            $table->string('id_prestasi')->nullable()->after('id');

            // 2. Definisi Foreign Key ke tabel 'prestasi_mahasiswas' pada kolom 'id_prestasi'
            $table->foreign('id_prestasi')
                  ->references('id_prestasi')
                  ->on('prestasi_mahasiswas')
                  ->onDelete('cascade')   // Jika data di prestasi_mahasiswas dihapus, relasi di skpi_data ikut terhapus
                  ->onUpdate('cascade');  // Jika id_prestasi diubah, di skpi_data otomatis ter-update
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('skpi_data', function (Blueprint $table) {
            // Drop Foreign Key terlebih dahulu sebelum menghapus kolom
            $table->dropForeign(['id_prestasi']);
            $table->dropColumn('id_prestasi');
        });
    }
};