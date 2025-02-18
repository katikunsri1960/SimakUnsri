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
            // Menambahkan kolom approved
            $table->integer('approved')->default('0')->after('abstrak_file');
            $table->string('wisuda_ke')->nullable()->after('no_ijazah');
            
            // Menghapus kolom judul dan lama_studi
            $table->dropColumn(['judul', 'lama_studi']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_wisuda', function (Blueprint $table) {
            // Menghapus kolom approved
            $table->dropColumn('approved');
            $table->dropColumn('wisuda_ke');
            
            // Menambahkan kembali kolom judul dan lama_studi
            $table->text('judul')->nullable();
            $table->integer('lama_studi')->nullable();
        });
    }
};
