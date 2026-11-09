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
        Schema::create('presma_data_dukungs', function (Blueprint $table) {
            $table->id();
            $table->integer('no_urut');
            $table->enum('kategori', ['visi', 'misi', 'foto', 'video']);
            $table->text('deskripsi'); // Untuk menyimpan teks Visi/Misi, path foto, atau URL video
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presma_data_dukungs');
    }
};