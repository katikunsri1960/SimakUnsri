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
        Schema::table('aktivitas_mahasiswas', function (Blueprint $table) {
            $table->boolean('feeder')->default(1)->after('id');
        });
        Schema::table('bimbing_mahasiswas', function (Blueprint $table) {
            $table->boolean('feeder')->default(1)->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('aktivitas_mahasiswas', function (Blueprint $table) {
            $table->dropColumn('feeder');
        });
        Schema::table('bimbing_mahasiswas', function (Blueprint $table) {
            $table->dropColumn('feeder');
        });
    }
};
