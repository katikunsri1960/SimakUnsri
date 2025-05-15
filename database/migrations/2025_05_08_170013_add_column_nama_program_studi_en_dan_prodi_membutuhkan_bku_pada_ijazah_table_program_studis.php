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
        Schema::table('program_studis', function (Blueprint $table) {
            $table->string('nama_program_studi_en')->nullable()->after('nama_program_studi');
            $table->boolean('bku_pada_ijazah')->default('0')->after('fakultas_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('program_studis', function (Blueprint $table) {
            $table->dropColumn('nama_program_studi_en');
            $table->dropColumn('bku_pada_ijazah');
        });
    }
};
