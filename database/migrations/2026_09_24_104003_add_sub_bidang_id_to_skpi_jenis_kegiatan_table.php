<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('skpi_jenis_kegiatan', function (Blueprint $table) {
            $table->foreignId('sub_bidang_id')
                  ->nullable()
                  ->after('bidang_id')
                  ->constrained('skpi_sub_bidang_kegiatan')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('skpi_jenis_kegiatan', function (Blueprint $table) {
            $table->dropForeign(['sub_bidang_id']);
            $table->dropColumn('sub_bidang_id');
        });
    }
};