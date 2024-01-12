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
        Schema::create('biodata_mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->string("nama_mahasiswa");
            $table->enum("jenis_kelamin", ["L", "P", "*"]);
            $table->string("tempat_lahir")->nullable();
            $table->string("tanggal_lahir")->nullable();
            $table->string("id_mahasiswa")->unique();
            $table->index('id_mahasiswa', 'idx_biodata');
            $table->string("id_agama");
            $table->string("nama_agama");
            $table->string("nik")->nullable();
            $table->string("nisn")->nullable();
            $table->string("npwp")->nullable();
            $table->string("id_negara");
            $table->string("kewarganegaraan");
            $table->text("jalan")->nullable();
            $table->string("dusun")->nullable();
            $table->string("rt")->nullable();
            $table->string("rw")->nullable();
            $table->string("kelurahan");
            $table->string("kode_pos")->nullable();
            $table->string("id_wilayah");
            $table->string("nama_wilayah");
            $table->string("id_jenis_tinggal")->nullable();
            $table->string("nama_jenis_tinggal")->nullable();
            $table->string("id_alat_transportasi")->nullable();
            $table->string("nama_alat_transportasi")->nullable();
            $table->string("telepon")->nullable();
            $table->string("handphone")->nullable();
            $table->string("email")->nullable();
            $table->boolean("penerima_kps");
            $table->string("nomor_kps")->nullable();
            $table->string("nik_ayah")->nullable();
            $table->string("nama_ayah")->nullable();
            $table->string("tanggal_lahir_ayah")->nullable();
            $table->string("id_pendidikan_ayah")->nullable();
            $table->string("nama_pendidikan_ayah")->nullable();
            $table->string("id_pekerjaan_ayah")->nullable();
            $table->string("nama_pekerjaan_ayah")->nullable();
            $table->string("id_penghasilan_ayah")->nullable();
            $table->string("nama_penghasilan_ayah")->nullable();
            $table->string("nik_ibu")->nullable();
            $table->string("nama_ibu_kandung");
            $table->string("tanggal_lahir_ibu")->nullable();
            $table->string("id_pendidikan_ibu")->nullable();
            $table->string("nama_pendidikan_ibu")->nullable();
            $table->string("id_pekerjaan_ibu")->nullable();
            $table->string("nama_pekerjaan_ibu")->nullable();
            $table->string("id_penghasilan_ibu")->nullable();
            $table->string("nama_penghasilan_ibu")->nullable();
            $table->string("nama_wali")->nullable();
            $table->string("tanggal_lahir_wali")->nullable();
            $table->string("id_pendidikan_wali")->nullable();
            $table->string("nama_pendidikan_wali")->nullable();
            $table->string("id_pekerjaan_wali")->nullable();
            $table->string("nama_pekerjaan_wali")->nullable();
            $table->string("id_penghasilan_wali")->nullable();
            $table->string("nama_penghasilan_wali")->nullable();
            $table->string("id_kebutuhan_khusus_mahasiswa");
            $table->string("nama_kebutuhan_khusus_mahasiswa");
            $table->string("id_kebutuhan_khusus_ayah");
            $table->string("nama_kebutuhan_khusus_ayah");
            $table->string("id_kebutuhan_khusus_ibu");
            $table->string("nama_kebutuhan_khusus_ibu");
            $table->string("status_sync");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('biodata_mahasiswas');
    }
};
