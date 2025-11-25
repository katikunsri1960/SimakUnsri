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
        Schema::create('kehadiran_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->string('kode_mata_kuliah', 200)->nullable();
            $table->string('username', 200)->nullable();
            $table->string('nama_kelas', 200)->nullable();
            $table->string('nama_mk', 200)->nullable();
            $table->string('session_date', 200)->nullable();
            $table->integer('session_id');
            $table->string('deskripsi_sesi', 200)->nullable();
            $table->integer('id_kehadiran')->nullable();
            $table->integer('status_id')->nullable();
            $table->string('status_mahasiswa', 200)->nullable();
            $table->timestamps();

            $table->unique(['username', 'session_id']);
        });
    }

    public function down(): void
    {
        //
    }
        
};
