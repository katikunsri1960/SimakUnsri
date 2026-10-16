<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('prestasi_mahasiswas', function (Blueprint $table) {
            $table->string('kategori_simkatmawa')->nullable()->after('id_aktivitas');
            $table->integer('jumlah_unit_peserta')->nullable()->after('kategori_simkatmawa');
            $table->string('bentuk')->nullable()->after('jumlah_unit_peserta');
            $table->string('file_foto_upp')->nullable()->after('bentuk');
            $table->string('file_undangan')->nullable()->after('file_foto_upp');
            $table->string('url_kompetisi')->nullable()->after('file_undangan');
        });
    }

    public function down(): void
    {
        Schema::table('prestasi_mahasiswas', function (Blueprint $table) {
            $table->dropColumn(['kategori_simkatmawa', 'jumlah_unit_peserta', 'bentuk', 'file_foto_upp', 'file_undangan', 'url_kompetisi']);
        });
    }
};