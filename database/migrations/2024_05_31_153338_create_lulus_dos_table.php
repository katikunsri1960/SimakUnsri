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
        Schema::create('lulus_dos', function (Blueprint $table) {
            $table->id();
            $table->string("id_registrasi_mahasiswa");
            $table->unique('id_registrasi_mahasiswa', 'unique_id_registrasi_mahasiswa');
            $table->string("id_mahasiswa")->nullable();
            $table->string("id_perguruan_tinggi")->nullable();
            $table->string("id_prodi")->nullable();
            $table->date("tgl_masuk_sp")->nullable();
            $table->date("tgl_keluar")->nullable();
            $table->string("skhun")->nullable();
            $table->string("no_peserta_ujian")->nullable();
            $table->string("no_seri_ijazah")->nullable();
            $table->date("tgl_create")->nullable();
            $table->string("sks_diakui")->nullable();
            $table->string("jalur_skripsi")->nullable();
            $table->text("judul_skripsi")->nullable();
            $table->string("bln_awal_bimbingan")->nullable();
            $table->string("bln_akhir_bimbingan")->nullable();
            $table->string("sk_yudisium")->nullable();
            $table->date("tgl_sk_yudisium")->nullable();
            $table->string("ipk")->nullable();
            $table->string("sert_prof")->nullable();
            $table->string("a_pindah_mhs_asing")->nullable();
            $table->string("id_pt_asal")->nullable();
            $table->string("id_prodi_asal")->nullable();
            $table->string("nm_pt_asal")->nullable();
            $table->string("nm_prodi_asal")->nullable();
            $table->string("id_jns_daftar")->nullable();
            $table->string("id_jns_keluar")->nullable();
            $table->string("id_jalur_masuk")->nullable();
            $table->string("id_pembiayaan")->nullable();
            $table->string("id_minat_bidang")->nullable();
            $table->string("bidang_mayor")->nullable();
            $table->string("bidang_minor")->nullable();
            $table->string("biaya_masuk_kuliah")->nullable();
            $table->string("namapt")->nullable();
            $table->string("id_jur")->nullable();
            $table->string("nm_jns_daftar")->nullable();
            $table->string("nm_smt")->nullable();
            $table->string("nim")->nullable();
            $table->string("nama_mahasiswa")->nullable();
            $table->string("nama_program_studi")->nullable();
            $table->string("angkatan")->nullable();
            $table->string("id_jenis_keluar")->nullable();
            $table->string("nama_jenis_keluar")->nullable();
            $table->string("tanggal_keluar")->nullable();
            $table->string("id_periode_keluar")->nullable();
            $table->string("keterangan")->nullable();
            $table->string("no_sertifikat_profesi")->nullable();
            $table->string("status_sync")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lulus_dos');
    }
};
