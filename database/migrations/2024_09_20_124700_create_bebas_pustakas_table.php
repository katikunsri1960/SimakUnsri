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
        Schema::create('bebas_pustakas', function (Blueprint $table) {
            $table->id();
            $table->string("id_registrasi_mahasiswa")->nullable();
            $table->foreign('id_registrasi_mahasiswa')->references('id_registrasi_mahasiswa')->on('riwayat_pendidikans')->onDelete('set null');
            $table->text('file_bebas_pustaka');
            $table->text('link_repo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bebas_pustakas');
    }
};
