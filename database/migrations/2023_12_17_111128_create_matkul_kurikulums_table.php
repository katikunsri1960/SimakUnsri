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
        Schema::create('matkul_kurikulums', function (Blueprint $table) {
            $table->id();
            $table->string('id_kurikulum');
            $table->foreign('id_kurikulum')->references('id_kurikulum')->on('list_kurikulums')->onDelete('cascade');
            $table->string('id_matkul');
            $table->foreign('id_matkul')->references('id_matkul')->on('mata_kuliahs')->onDelete('cascade');
            $table->integer('semester')->nullable();
            $table->boolean('apakah_wajib')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matkul_kurikulums');
    }
};
