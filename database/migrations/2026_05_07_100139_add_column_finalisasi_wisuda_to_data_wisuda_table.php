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

            $table->tinyInteger('finalisasi_wisuda')
                ->default(1)
                ->after('finalisasi_data');

            $table->tinyInteger('approved_wisuda')
                ->default(3)
                ->after('approved');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_wisuda', function (Blueprint $table) {

            $table->dropColumn([
                'finalisasi_wisuda',
                'approved_wisuda'
            ]);

        });
    }
};