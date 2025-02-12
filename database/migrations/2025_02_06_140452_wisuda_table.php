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
        Schema::create('data_wisuda', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_fakultas')->nullable();
            $table->foreign('id_fakultas')->references('id')->on('fakultas')->onDelete('set null');
            $table->string('id_prodi')->nullable();
            $table->foreign('id_prodi')->references('id_prodi')->on('program_studis')->onDelete('set null');
            $table->string('kosentrasi')->nullable();
            $table->string('pas_foto')->nullable(); // Path ke file pas foto
            $table->string('lokasi_kuliah')->nullable();
            $table->string('jalur_masuk')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->decimal('ipk', 4, 2)->nullable(); // Nilai IPK dengan presisi 4 angka, 2 desimal
            $table->text('alamat')->nullable();
            $table->string('no_telp')->nullable();
            $table->string('email')->nullable();
            $table->string('nama_orang_tua')->nullable();
            $table->text('alamat_orang_tua')->nullable();
            $table->date('tgl_daftar')->nullable();
            $table->date('tgl_yudisium')->nullable();
            $table->integer('lama_studi')->nullable(); // Lama studi dalam bulan atau tahun
            $table->string('judul_ta')->nullable();
            $table->text('abstrak_ta')->nullable();
            $table->string('abstrak_file')->nullable(); // Path ke file abstrak
            $table->timestamps(); // Menambahkan kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_wisuda');
    }
};