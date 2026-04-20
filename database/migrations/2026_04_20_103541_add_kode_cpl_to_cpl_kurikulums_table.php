<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cpl_kurikulums', function (Blueprint $table) {
            $table->string('kode_cpl')->after('id_kurikulum');

            $table->unique(['id_kurikulum', 'kode_cpl'], 'cpl_kurikulum_unique');
        });
    }

    public function down(): void
    {
        Schema::table('cpl_kurikulums', function (Blueprint $table) {
            $table->dropColumn('kode_cpl');
        });
    }
};
