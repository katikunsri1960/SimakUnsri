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
        Schema::create('program_studis', function (Blueprint $table) {
            $table->id();
            $table->string('id_prodi');
            $table->string('nama_prodi');
            $table->string('kode_prodi', 15);
            $table->string('status');
            $table->string('id_jenjang_pendidikan');
            $table->string('nama_jenjang_pendidikan');
            $table->foreignId('fakultas_id')->constrained('fakultas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_studis');
    }
};
