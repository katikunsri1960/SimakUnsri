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
            $table->text('judul_eng')->nullable()->after('lokasi_kuliah'); // Judul dalam bahasa Inggris
            $table->string('abstrak_file_eng')->nullable()->after('abstrak_file'); // Path ke file abstrak bahasa inggris
            $table->string('ijazah_terakhir_file')->nullable()->after('abstrak_file_eng'); // Path ke file ijazah terakhir
            
            $table->dropColumn('alamat_orang_tua'); // Menghapus kolom alamat_orang_tua jika ada
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_wisuda', function (Blueprint $table) {
            $table->dropColumn('judul_eng');
            $table->dropColumn('abstrak_file_eng');
            $table->dropColumn('ijazah_terakhir_file');
        });
    }
};
