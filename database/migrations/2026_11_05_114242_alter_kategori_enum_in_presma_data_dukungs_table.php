<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Mengubah kolom enum kategori agar mendukung 'berkas'
        DB::statement("ALTER TABLE presma_data_dukungs MODIFY COLUMN kategori ENUM('visi', 'misi', 'foto', 'video', 'berkas') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE presma_data_dukungs MODIFY COLUMN kategori ENUM('visi', 'misi', 'foto', 'video') NOT NULL");
    }
};