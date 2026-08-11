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
        DB::statement("
            ALTER TABLE users
            MODIFY COLUMN role ENUM(
                'admin',
                'univ',
                'fakultas',
                'prodi',
                'dosen',
                'mahasiswa',
                'perpus',
                'dppti',
                'dak',
                'ditmawa',
                'dppm'
            ) NOT NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("
            ALTER TABLE users
            MODIFY COLUMN role ENUM(
                'admin',
                'univ',
                'fakultas',
                'prodi',
                'dosen',
                'mahasiswa',
                'perpus',
                'dppm'
            ) NOT NULL
        ");
    }
};