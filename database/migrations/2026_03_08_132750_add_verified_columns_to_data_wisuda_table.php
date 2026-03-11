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
        Schema::table('data_wisuda', function (Blueprint $table) {

            $table->boolean('verified_induk')->default(1)->after('alasan_pembatalan');
            $table->boolean('verified_akademik')->default(1)->after('verified_induk');
            $table->boolean('verified_ta')->default(1)->after('verified_akademik');
            $table->boolean('verified_wisuda')->default(1)->after('verified_ta');
            $table->boolean('verified_skpi')->default(1)->after('verified_wisuda');
            $table->boolean('finalisasi_data')->default(1)->after('verified_skpi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_wisuda', function (Blueprint $table) {

            $table->dropColumn([
                'verified_induk',
                'verified_akademik',
                'verified_ta',
                'verified_wisuda',
                'verified_skpi',
                'finalisasi_data'
            ]);

        });
    }
};
