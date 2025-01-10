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
        Schema::table('pembayaran_manual_mahasiswas', function (Blueprint $table) {
            $table->index(['id_registrasi_mahasiswa', 'id_semester'], 'idx_id_reg_id_semester');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayaran_manual_mahasiswas', function (Blueprint $table) {
            $table->dropIndex('idx_id_reg_id_semester');
        });
    }
};
