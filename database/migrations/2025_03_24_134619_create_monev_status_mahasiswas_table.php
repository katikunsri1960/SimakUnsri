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
        Schema::create('monev_status_mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->string('id_semester');
            $table->foreign('id_semester')->references('id_semester')->on('semesters')->onDelete('cascade');
            $table->string('id_prodi');
            $table->index('id_prodi', 'idx_prodi');
            $table->unique(['id_semester', 'id_prodi'], 'unique_id_semester_prodi');
            $table->integer('mahasiswa_lewat_semester')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monev_status_mahasiswas');
    }
};
