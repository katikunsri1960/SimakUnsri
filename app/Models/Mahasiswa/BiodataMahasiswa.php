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

    public function getJenisKelaminAttribute($value)
    {
        if ($value==="L")
        {
            return "Laki-laki";
        }
        elseif ($value==="P")
        {
            return "Perempuan";
        }
        else
        {
            return "Lainnya";
        }
    }

    

    // public function getUKTAttribute($value)
    // {
    //     return number_format($value, 0, ',', '.');
    // }

}
