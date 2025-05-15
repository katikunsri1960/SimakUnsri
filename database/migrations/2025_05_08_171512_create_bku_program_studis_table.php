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
        Schema::create('bku_program_studis', function (Blueprint $table) {
            $table->id();
            $table->string('id_prodi');
            $table->foreign('id_prodi')->references('id_prodi')->on('program_studis')->onDelete('cascade');
            $table->string('bku_prodi_id')->nullable();
            $table->string('bku_prodi_en')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bku_program_studis');
    }
};
