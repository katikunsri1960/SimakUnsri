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
        Schema::table('prestasi_mahasiswas', function (Blueprint $table) {
            // Ubah kolom approved dengan default 3 dan tambahkan komentar deskripsi status
            $table->integer('approved')
                ->default(3)
                ->comment('0: Pending Prodi, 1: Approved Prodi, 2: Approved Fakultas, 3: Approved Ditmawa/Selesai, 97: Decline Prodi, 98: Decline Fakultas, 99: Decline Ditmawa')
                ->change();

            // Menambahkan kolom alasan_pembatalan bertipe text setelah kolom approved
            $table->text('alasan_pembatalan')->nullable()->after('id_aktivitas');

            // Kolom tracking sync SIMKATMAWA (0: Belum Sync, 1: Sudah Sync)
            $table->boolean('simkatmawa')->default(1)->after('alasan_pembatalan');
            
            // Kolom tracking sync PDUnsri (0: Belum Sync, 1: Sudah Sync)
            $table->boolean('pdunsri')->default(1)->after('simkatmawa');
            
            // Kolom penampung ID balikan/referensi dari API SIMKATMAWA
            $table->string('id_simkatmawa')->nullable()->after('pdunsri');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prestasi_mahasiswas', function (Blueprint $table) {
            $table->dropColumn(['alasan_pembatalan', 'simkatmawa', 'pdunsri', 'id_simkatmawa']);
            
            // Mengembalikan atribut approved (sesuaikan default lama jika perlu)
            $table->integer('approved')->default(0)->comment(null)->change();
        });
    }
};