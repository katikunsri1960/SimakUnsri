<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('import_pembayaran', function (Blueprint $table) {

            $table->string('id_record_pembayaran', 70)->primary();
            $table->string('id_record_tagihan', 30)->index();

            $table->dateTime('waktu_transaksi');
            $table->string('nomor_pembayaran', 30)->index();

            $table->string('kode_unik_transaksi_bank', 30);
            $table->string('waktu_transaksi_bank', 20);

            $table->string('kode_bank', 10)->index();
            $table->string('kanal_bayar_bank', 20);

            $table->string('kode_terminal_bank', 20)->nullable();

            $table->double('total_nilai_pembayaran');
            $table->integer('status_pembayaran')->default(0);

            $table->string('id_record_rekonsiliasi', 30)->nullable();
            $table->string('id_record_settlement', 30)->nullable();
            $table->string('billref', 30)->nullable();

            $table->string('metode_pembayaran', 10)->default('H2H');

            $table->string('catatan', 200)->nullable();

            $table->string('key_val_1', 255)->nullable();
            $table->string('key_val_2', 255)->nullable();
            $table->string('key_val_3', 255)->nullable();
            $table->string('key_val_4', 255)->nullable();
            $table->string('key_val_5', 255)->nullable();

            $table->timestamps();

            $table->index(['id_record_tagihan', 'nomor_pembayaran']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('import_pembayaran');
    }
};
