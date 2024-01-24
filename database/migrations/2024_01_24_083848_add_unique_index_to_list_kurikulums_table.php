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
            $table->unique('id_kurikulum', 'unique_id_kurikulum');
            $table->foreign('id_prodi')->references('id_prodi')->on('program_studis')->onDelete('cascade');
            $table->foreign('id_semester')->references('id_semester')->on('semesters')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('list_kurikulums', function (Blueprint $table) {
            $table->dropUnique('unique_id_kurikulum');
            $table->dropForeign('list_kurikulums_id_prodi_foreign');
            $table->dropForeign('list_kurikulums_id_semester_foreign');
        });
    }
};
