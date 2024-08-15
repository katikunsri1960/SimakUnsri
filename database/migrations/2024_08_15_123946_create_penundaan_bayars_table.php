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
        Schema::create('penundaan_bayars', function (Blueprint $table) {
            $table->id();
            $table->string('id_registrasi_mahasiswa')->nullable();
            $table->foreign('id_registrasi_mahasiswa')->references('id_registrasi_mahasiswa')->on('riwayat_pendidikans')->onDelete('set null');
            $table->string('nim');
            $table->string('id_semester')->nullable();
            $table->foreign('id_semester')->references('id_semester')->on('semesters')->onDelete('set null');
            $table->integer('status')->default(0)->comment('0: Diajukan, 2: Disetujui Prodi, 3: Disetujui Fakultas, 4: Disetujui BAK, 5: Ditolak');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penundaan_bayars');
    }
};
