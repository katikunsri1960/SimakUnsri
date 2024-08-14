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
        Schema::table('beasiswa_mahasiswas', function (Blueprint $table) {
            $table->foreignId('id_pembiayaan')->after('id_jenis_beasiswa')->nullable()->constrained('pembiayaans')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('beasiswa_mahasiswas', function (Blueprint $table) {
            $table->dropColumn('id_pembiayaan');
        });
    }
};
