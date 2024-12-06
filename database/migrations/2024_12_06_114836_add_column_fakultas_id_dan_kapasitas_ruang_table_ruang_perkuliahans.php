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
        Schema::table('ruang_perkuliahans', function (Blueprint $table) {
            $table->foreignId('fakultas_id')
                ->nullable()
                ->after('id_prodi')
                ->constrained('fakultas') // Mengacu pada tabel fakultas
                ->onDelete('set null');
            
            $table->integer('kapasitas_ruang')->after('fakultas_id')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ruang_perkuliahans', function (Blueprint $table) {
            // Hapus foreign key sebelum menghapus kolom
            $table->dropForeign(['fakultas_id']);
            $table->dropColumn('fakultas_id');

            $table->dropColumn('kapasitas_ruang');
        });
    }
};
