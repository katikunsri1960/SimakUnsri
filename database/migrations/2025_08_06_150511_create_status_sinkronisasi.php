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
        Schema::create('status_sinkronisasi', function (Blueprint $table) {
            $table->id();
            $table->uuid('batch_id')->nullable(); // ID unik untuk batch
            $table->string('tipe'); // Mahasiswa/Dosen/Transkrip, dll
            $table->string('status')->default('pending'); // pending, processing, success, failed
            $table->integer('total')->default(0);
            $table->integer('proses')->default(0);
            $table->integer('gagal')->default(0);
            $table->text('pesan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('status_sinkronisasi');
    }
};
