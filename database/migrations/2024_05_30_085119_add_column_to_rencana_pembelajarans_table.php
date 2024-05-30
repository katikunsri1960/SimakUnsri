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
        Schema::table('rencana_pembelajarans', function (Blueprint $table) {
            $table->boolean('feeder')->default(1)->after('id');
            $table->boolean('approved')->default(0)->after('feeder');
        });

        Schema::table('dosen_pengajar_kelas_kuliahs', function (Blueprint $table) {
            $table->boolean('feeder')->default(1)->after('id');
            $table->integer('urutan')->nullable()->after('id_dosen');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rencana_pembelajarans', function (Blueprint $table) {
            $table->dropColumn(['feeder', 'approved']);
        });

        Schema::table('dosen_pengajar_kelas_kuliahs', function (Blueprint $table) {
            $table->dropColumn(['feeder', 'urutan']);
        });
    }
};
