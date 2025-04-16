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
        Schema::create('profil_pts', function (Blueprint $table) {
            $table->id();
            $table->string('id_perguruan_tinggi')->unique();
            $table->string('kode_perguruan_tinggi')->nullable();
            $table->string('nama_perguruan_tinggi')->nullable();
            $table->string('telepon')->nullable();
            $table->string('faximile')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('jalan')->nullable();
            $table->string('dusun')->nullable();
            $table->string('rt_rw')->nullable();
            $table->string('kelurahan')->nullable();
            $table->string('kode_pos')->nullable();
            $table->string('id_wilayah')->nullable();
            $table->string('nama_wilayah')->nullable();
            $table->string('lintang_bujur')->nullable();
            $table->string('bank')->nullable();
            $table->string('unit_cabang')->nullable();
            $table->string('nomor_rekening')->nullable();
            $table->string('mbs')->nullable();
            $table->string('luas_tanah_milik')->nullable();
            $table->string('luas_tanah_bukan_milik')->nullable();
            $table->string('sk_pendirian')->nullable();
            $table->string('tanggal_sk_pendirian')->nullable();
            $table->string('id_status_milik')->nullable();
            $table->string('nama_status_milik')->nullable();
            $table->string('status_perguruan_tinggi')->nullable();
            $table->string('sk_izin_operasional')->nullable();
            $table->string('tanggal_izin_operasional')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profil_pts');
    }
};
