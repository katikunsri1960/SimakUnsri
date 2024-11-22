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
        Schema::table('kelas_kuliahs', function (Blueprint $table) {
            // Tambahkan foreign key lokasi_ujian_id yang merujuk ke ruang_perkuliahans
            $table->foreignId('lokasi_ujian_id')
                ->nullable()
                ->after('jadwal_jam_selesai')
                ->constrained('ruang_perkuliahans') // Mengacu pada tabel ruang_perkuliahans
                ->onDelete('set null');
            
            // Tambahkan kolom jadwal mulai dan selesai ujian
            $table->datetime('jadwal_mulai_ujian')->after('lokasi_ujian_id')->nullable();
            $table->datetime('jadwal_selesai_ujian')->after('jadwal_mulai_ujian')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelas_kuliahs', function (Blueprint $table) {
            // Hapus foreign key sebelum menghapus kolom
            $table->dropForeign(['lokasi_ujian_id']);
            $table->dropColumn('lokasi_ujian_id');
            
            // Hapus kolom jadwal mulai dan selesai ujian
            $table->dropColumn('jadwal_mulai_ujian');
            $table->dropColumn('jadwal_selesai_ujian');
        });
    }
};
