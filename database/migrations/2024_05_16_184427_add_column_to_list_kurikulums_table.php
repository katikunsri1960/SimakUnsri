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
        Schema::table('list_kurikulums', function (Blueprint $table) {
            $table->boolean('is_active')->default(0)->after('sk_kurikulum');
        });

        Schema::table('semester_aktifs', function (Blueprint $table) {
            $table->date('batas_isi_nilai')->nullable()->after('krs_selesai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('list_kurikulums', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });

        Schema::table('semester_aktifs', function (Blueprint $table) {
            $table->dropColumn('batas_isi_nilai');
        });
    }
};
