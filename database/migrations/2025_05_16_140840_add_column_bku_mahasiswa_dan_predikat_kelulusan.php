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
        Schema::table('data_wisuda', function (Blueprint $table) {
            // Add only if not exists
            if (!Schema::hasColumn('data_wisuda', 'id_bku_prodi')) {

                $table->foreignId('id_bku_prodi')->nullable()->after('abstrak_file')->constrained('bku_program_studis')->nullOnDelete();
            }

            if (!Schema::hasColumn('data_wisuda', 'id_predikat_kelulusan')) {
                $table->foreignId('id_predikat_kelulusan')->nullable()->after('id_bku_prodi')->constrained('predikat_kelulusans')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_wisuda', function (Blueprint $table) {
            // Drop foreign first, then column
            if (Schema::hasColumn('data_wisuda', 'id_predikat_kelulusan')) {
                $table->dropColumn('id_predikat_kelulusan');
            }

            if (Schema::hasColumn('data_wisuda', 'id_bku_prodi')) {
                $table->dropColumn('id_bku_prodi');
            }
        });
    }
};

