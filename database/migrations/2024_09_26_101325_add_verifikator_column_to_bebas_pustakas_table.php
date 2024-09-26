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
        Schema::table('bebas_pustakas', function (Blueprint $table) {
            $table->string('verifikator')->nullable()->after('link_repo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bebas_pustakas', function (Blueprint $table) {
            $table->dropColumn('verifikator');
        });
    }
};
