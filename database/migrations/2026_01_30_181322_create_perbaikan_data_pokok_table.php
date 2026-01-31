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
        Schema::create('perbaikan_data_pokok', function (Blueprint $table) {
            $table->id();

            $table->string('id_registrasi_mahasiswa')->nullable();
            $table->string('nama_mahasiswa')->nullable();
            $table->string('nim')->nullable();

            $table->string('nama_perbaikan')->nullable();
            $table->string('tmpt_perbaikan')->nullable();
            $table->date('tgl_perbaikan')->nullable();

            $table->timestamps();

            // UNIQUE: 1 data per mahasiswa
            $table->unique('id_registrasi_mahasiswa', 'uniq_pdp_id_reg_mhs');

            // Foreign Key
            $table->foreign('id_registrasi_mahasiswa', 'fk_pdp_reg_mhs')
                ->references('id_registrasi_mahasiswa')
                ->on('riwayat_pendidikans')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perbaikan_data_pokok');
    }
};
