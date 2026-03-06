<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('import_tagihan', function (Blueprint $table) {

            $table->string('id_record_tagihan', 30)->primary();
            $table->string('nomor_pembayaran', 30)->index();
            $table->string('nama', 255);

            $table->string('kode_fakultas', 20)->nullable()->index();
            $table->string('nama_fakultas', 255)->nullable();

            $table->string('kode_prodi', 20)->nullable()->index();
            $table->string('nama_prodi', 255)->nullable();

            $table->string('kode_periode', 20)->nullable()->index();
            $table->string('nama_periode', 255)->nullable();

            $table->integer('is_tagihan_aktif');
            $table->dateTime('waktu_berlaku')->nullable();
            $table->dateTime('waktu_berakhir')->nullable();

            $table->string('strata', 255)->nullable();
            $table->string('angkatan', 255)->nullable()->index();

            $table->integer('urutan_antrian')->default(0);
            $table->double('total_nilai_tagihan');

            $table->string('nomor_induk', 30)->index();

            $table->string('pembayaran_atau_voucher', 20)->default('PEMBAYARAN');

            $table->string('voucher_nama', 255)->nullable();
            $table->string('voucher_nama_fakultas', 255)->nullable();
            $table->string('voucher_nama_prodi', 255)->nullable();
            $table->string('voucher_nama_periode', 255)->nullable();

            $table->timestamps();

            $table->index(['nomor_induk', 'kode_periode']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('import_tagihan');
    }
};
