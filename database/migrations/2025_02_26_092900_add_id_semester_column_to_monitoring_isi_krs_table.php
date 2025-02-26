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
        Schema::table('monitoring_isi_krs', function (Blueprint $table) {
            // drop unique constraint dari id_prodi column
            $table->dropUnique('monitoring_isi_krs_id_prodi_unique');
            $table->string('id_semester')->nullable()->after('id');
            $table->foreign('id_semester')->references('id_semester')->on('semesters')->onDelete('cascade');
            // unique key untuk id_prodi dan id_semester
            $table->unique(['id_prodi', 'id_semester'], 'monitoring_isi_krs_id_prodi_id_semester_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monitoring_isi_krs', function (Blueprint $table) {
            $table->dropForeign(['id_semester']);
            $table->dropUnique('monitoring_isi_krs_id_prodi_id_semester_unique');
            $table->dropColumn('id_semester');
            $table->unique('id_prodi');
        });
    }
};
