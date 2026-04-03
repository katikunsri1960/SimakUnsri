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
            $table->string('id_kurikulum')->required();
            $table->text('nama_cpl')->required();
            $table->timestamps();

            // optional relasi (jika ada tabel kurikulum)
            $table->foreign('id_kurikulum')
                  ->references('id_kurikulum')
                  ->on('list_kurikulums')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cpl_kurikulums');
    }
};