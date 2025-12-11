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
            // Menambahkan kolom no_urut
            $table->integer('no_urut')->nullable()->after('wisuda_ke');

            // Kombinasi no_urut + wisuda_ke harus unik
            $table->unique(['no_urut', 'wisuda_ke'], 'unique_no_urut_wisuda');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_wisuda', function (Blueprint $table) {
            // Hapus unique constraint
            $table->dropUnique('unique_no_urut_wisuda');

            // Hapus kolom
            $table->dropColumn('no_urut');
        });
    }
};
