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
        Schema::create('bimbing_mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->string("id_bimbing_mahasiswa")->nullable()->unique();
            $table->string("id_aktivitas")->nullable();
            $table->text("judul")->nullable();
            $table->integer("id_kategori_kegiatan")->nullable();
            $table->foreign("id_kategori_kegiatan")->references("id_kategori_kegiatan")->on("kategori_kegiatans")->onDelete("set null");
            $table->text("nama_kategori_kegiatan")->nullable();
            $table->string("id_dosen")->nullable();
            $table->foreign("id_dosen")->references("id_dosen")->on("biodata_dosens")->onDelete("set null");
            $table->unique(["id_aktivitas", "id_dosen"], 'unique_aktivitas_bimbing');
            $table->string("nidn")->nullable();
            $table->string("nama_dosen")->nullable();
            $table->integer("pembimbing_ke")->nullable();
            $table->string("status_sync")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bimbing_mahasiswas');
    }
};
