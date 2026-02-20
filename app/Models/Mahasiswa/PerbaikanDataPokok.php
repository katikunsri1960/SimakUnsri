<?php

namespace App\Models\Mahasiswa;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerbaikanDataPokok extends Model
{
    use HasFactory;
    protected $table = 'perbaikan_data_pokok';
    protected $guarded = [];

    public function lulus_do()
    {
        return $this->hasOne(LulusDO::class, 'id_registrasi_mahasiswa', 'id_registrasi_mahasiswa');
    }

    public function data_wisuda()
    {
        return $this->hasOne(Wisuda::class, 'id_registrasi_mahasiswa', 'id_registrasi_mahasiswa');
    }

    public function riwayat_pendidikan()
    {
        return $this->belongsTo(RiwayatPendidikan::class, 'id_registrasi_mahasiswa', 'id_registrasi_mahasiswa');
    }
}
