<?php

namespace App\Models\Mahasiswa;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BiodataMahasiswa extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function riwayat_pendidikan()
    {
        return $this->hasMany(RiwayatPendidikan::class, 'id_mahasiswa', 'id_mahasiswa');
    }

}
