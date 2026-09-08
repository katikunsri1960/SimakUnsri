<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sister_list_jabatan_fungsional', function (Blueprint $table) {
            $table->string('id_sdm')->nullable()->index();
            $table->string('id_stat_pegawai')->nullable();
            $table->string('id')->nullable()->unique(); // wajib untuk upsert
            $table->string('jabatan_fungsional')->nullable();
            $table->string('nm_stat_pegawai')->nullable();
            $table->string('sk')->nullable();
            $table->date('tanggal_mulai')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sister_list_jabatan_fungsional');
    }
};