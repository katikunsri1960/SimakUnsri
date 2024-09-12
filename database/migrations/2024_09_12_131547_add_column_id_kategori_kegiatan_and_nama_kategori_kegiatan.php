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
        Schema::table('nilai_sidang_mahasiswa', function (Blueprint $table) {
            $table->string('id_kategori_kegiatan')->nullable()->after('id_dosen');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nilai_sidang_mahasiswa', function (Blueprint $table) {
            $table->dropColumn('id_kategori_kegiatan');
        });
    }
};
