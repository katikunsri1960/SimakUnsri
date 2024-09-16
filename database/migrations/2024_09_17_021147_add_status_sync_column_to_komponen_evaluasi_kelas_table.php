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
        Schema::table('komponen_evaluasi_kelas', function (Blueprint $table) {
            $table->string('status_sync')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('komponen_evaluasi_kelas', function (Blueprint $table) {
            $table->dropColumn('status_sync');
        });
    }
};
