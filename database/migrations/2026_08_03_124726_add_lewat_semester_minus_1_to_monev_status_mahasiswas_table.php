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
        Schema::table('monev_status_mahasiswas', function (Blueprint $table) {
            $table->integer('lewat_semester_minus_1')
                  ->default(0)
                  ->after('mahasiswa_lewat_semester');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monev_status_mahasiswas', function (Blueprint $table) {
            $table->dropColumn('lewat_semester_minus_1');
        });
    }
};