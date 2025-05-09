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
        Schema::table('biodata_mahasiswas', function (Blueprint $table) {
            $table->string('no_hp_ayah')->after('nama_penghasilan_ayah')->nullable();
            $table->string('no_hp_ibu')->after('nama_penghasilan_ibu')->nullable();
            $table->string('alamat_orang_tua')->nullable()->after('no_hp_ibu');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('biodata_mahasiswas', function (Blueprint $table) {
            $table->dropColumn('no_hp_ayah');
            $table->dropColumn('no_hp_ibu');
            $table->dropColumn('alamat_orang_tua');
        });
    }
};
