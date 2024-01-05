<?php

namespace App\Models\Mahasiswa;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatPendidikan extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function biodata()
    {
        return $this->belongsTo(BiodataMahasiswa::class, 'id_mahasiswa', 'id_mahasiswa');
    }
}
