<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cpl_kurikulums', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_kurikulum');
            $table->text('nama_cpl');
            $table->timestamps();

            // optional relasi (jika ada tabel kurikulum)
            $table->foreign('id_kurikulum')
                  ->references('id')
                  ->on('list_kurikulums')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cpl_kurikulums');
    }
};