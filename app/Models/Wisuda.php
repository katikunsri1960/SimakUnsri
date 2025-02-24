<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Mahasiswa\RiwayatPendidikan;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Wisuda extends Model
{
    use HasFactory;
    protected $table = 'data_wisuda';

    // App\Models\Wisuda.php
    protected $fillable = [
        'id_perguruan_tinggi',
        'id_registrasi_mahasiswa',
        'id_prodi',
        'tgl_masuk',
        'tgl_keluar',
        'lama_studi',
        'no_peserta_ujian',
        'sks_diakui',
        'no_ijazah',
        'wisuda_ke',
        'no_sk_yudisium',
        'tgl_sk_yudisium',
        'id_aktivitas',
        'keterangan',
        'angkatan',
        'nim',
        'nama_mahasiswa',
        'kosentrasi',
        'pas_foto',
        'lokasi_kuliah',
        'abstrak_ta',
        'abstrak_file',
        'approved',
        'alasan_pembatalan',
        // KOLOM SEMENTARA
        'bebas_pustaka',
        'useptData',
    ];

    public function prodi()
    {
        return $this->belongsTo(ProgramStudi::class, 'id_prodi', 'id_prodi');
    }

    public function riwayat_pendidikan()
    {
        return $this->belongsTo(RiwayatPendidikan::class, 'id_registrasi_mahasiswa', 'id_registrasi_mahasiswa');
    }


}
