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
        Schema::create('biodata_dosens', function (Blueprint $table) {
            $table->id();
            $table->string("id_dosen")->nullable()->unique();
            $table->index('id_dosen', 'idx_dosen');
            $table->string("nama_dosen")->nullable();
            $table->string("tempat_lahir")->nullable();
            $table->date("tanggal_lahir")->nullable();
            $table->string("jenis_kelamin")->nullable();
            $table->integer("id_agama")->nullable();
            $table->string("nama_agama")->nullable();
            $table->string("id_status_aktif")->nullable();
            $table->string("nama_status_aktif")->nullable();
            $table->string("nidn")->nullable();
            $table->string("nama_ibu_kandung")->nullable();
            $table->string("nik")->nullable();
            $table->string("nip")->nullable();
            $table->string("npwp")->nullable();
            $table->string("id_jenis_sdm")->nullable();
            $table->string("nama_jenis_sdm")->nullable();
            $table->string("no_sk_cpns")->nullable();
            $table->string("tanggal_sk_cpns")->nullable();
            $table->string("no_sk_pengangkatan")->nullable();
            $table->string("mulai_sk_pengangkatan")->nullable();
            $table->integer("id_lembaga_pengangkatan")->nullable();
            $table->string("nama_lembaga_pengangkatan")->nullable();
            $table->string("id_pangkat_golongan")->nullable();
            $table->string("nama_pangkat_golongan")->nullable();
            $table->string("id_sumber_gaji")->nullable();
            $table->string("nama_sumber_gaji")->nullable();
            $table->text("jalan")->nullable();
            $table->string("dusun")->nullable();
            $table->string("rt")->nullable();
            $table->string("rw")->nullable();
            $table->string("ds_kel")->nullable();
            $table->string("kode_pos")->nullable();
            $table->string("id_wilayah")->nullable();
            $table->index('id_wilayah', 'idx_wilayah');
            $table->string("nama_wilayah")->nullable();
            $table->string("telepon")->nullable();
            $table->string("handphone")->nullable();
            $table->string("email")->nullable();
            $table->string("status_pernikahan")->nullable();
            $table->string("nama_suami_istri")->nullable();
            $table->string("nip_suami_istri")->nullable();
            $table->string("tanggal_mulai_pns")->nullable();
            $table->string("id_pekerjaan_suami_istri")->nullable();
            $table->string("nama_pekerjaan_suami_istri")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('biodata_dosens');
    }
};
