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
        Schema::table('batas_isi_krs_manual', function (Blueprint $table) {
            $table->date('mulai_isi_krs')->nullable()->after('id_semester');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('batas_isi_krs_manual', function (Blueprint $table) {
            $table->dropColumn('mulai_isi_krs');
        });
    }
};
