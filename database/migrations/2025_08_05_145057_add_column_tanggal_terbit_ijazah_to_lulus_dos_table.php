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
        Schema::table('lulus_dos', function (Blueprint $table) {
            $table->string('tanggal_terbit_ijazah')->nullable()->after('no_sertifikat_profesi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lulus_dos', function (Blueprint $table) {
            $table->dropColumn('tanggal_terbit_ijazah');
        });
    }
};
