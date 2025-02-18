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
        Schema::create('pejabat_universitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jabatan_id')->constrained('pejabat_universitas_jabatans')->onDelete('cascade');
            $table->string('nama');
            $table->string('nip')->nullable();
            $table->string('gelar_depan')->nullable();
            $table->string('gelar_belakang')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pejabat_universitas');
    }
};
