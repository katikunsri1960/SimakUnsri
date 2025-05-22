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
        Schema::create('file_fakultas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fakultas_id');
            $table->foreign('fakultas_id')->references('id')->on('fakultas')->unique()->onDelete('cascade');
            $table->string('nama_file')->unique();
            $table->date('tgl_surat');
            $table->string('tgl_kegiatan');
            $table->string('dir_file');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_fakultas');
    }
};
