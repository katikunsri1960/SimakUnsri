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
        Schema::table('biodata_dosens', function (Blueprint $table) {
            $table->string('gelar_depan', 50)->nullable()->after('nama_dosen');
            $table->string('gelar_belakang', 50)->nullable()->after('gelar_depan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('biodata_dosens', function (Blueprint $table) {
            $table->dropColumn(['gelar_depan', 'gelar_belakang']);
        });
    }
};
