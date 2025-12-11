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
        Schema::table('skala_nilais', function (Blueprint $table) {

            // Rename columns
            $table->renameColumn('bobot_minimum', 'bobot_nilai_min');
            $table->renameColumn('bobot_maksimum', 'bobot_nilai_maks');
        });

        // Ubah tipe setelah rename (jika langsung tidak bisa karena MySQL)
        Schema::table('skala_nilais', function (Blueprint $table) {
            $table->double('bobot_nilai_min', 8, 2)->change();
            $table->double('bobot_nilai_maks', 8, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('skala_nilais', function (Blueprint $table) {

            // Kembalikan ke nama awal
            $table->renameColumn('bobot_nilai_min', 'bobot_minimum');
            $table->renameColumn('bobot_nilai_maks', 'bobot_maksimum');
        });

        Schema::table('skala_nilais', function (Blueprint $table) {
            $table->float('bobot_minimum', 8, 2)->change();
            $table->float('bobot_maksimum', 8, 2)->change();
        });
    }
};
