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

            $table->year('tahun')->nullable()->after('nama_kegiatan');
            $table->text('alasan_pembatalan')->nullable()->after('approved');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('skpi_data', function (Blueprint $table) {

            $table->dropColumn(['tahun','alasan_pembatalan']);

        });
    }
};
