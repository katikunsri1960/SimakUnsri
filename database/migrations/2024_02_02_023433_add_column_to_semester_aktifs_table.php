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
        Schema::table('semester_aktifs', function (Blueprint $table) {
            $table->date('krs_mulai')->after('id_semester');
            $table->date('krs_selesai')->after('krs_mulai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('semester_aktifs', function (Blueprint $table) {
            $table->dropColumn('krs_mulai');
            $table->dropColumn('krs_selesai');
        });
    }
};
