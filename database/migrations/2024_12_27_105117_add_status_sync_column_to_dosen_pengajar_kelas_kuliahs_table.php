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
        Schema::table('dosen_pengajar_kelas_kuliahs', function (Blueprint $table) {
            $table->string('status_sync')->nullable()->after('id_semester');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dosen_pengajar_kelas_kuliahs', function (Blueprint $table) {
            $table->dropColumn('status_sync');
        });
    }
};
