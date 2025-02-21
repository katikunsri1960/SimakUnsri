<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'wisuda_ke',
        'sks_diakui',
        'id_aktivitas',
        'angkatan',
        'nim',
        'nama_mahasiswa',
        'kosentrasi',
        'pas_foto',
        'lokasi_kuliah',
        'abstrak_ta',
        'no_sk_yudisium',
        'tgl_sk_yudisium',
        'approved',
    ];

    public function prodi()
    {
        return $this->belongsTo(ProgramStudi::class, 'id_prodi', 'id_prodi');
    }
}
