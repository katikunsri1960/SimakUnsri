<?php

namespace App\Models\Dosen;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Dosen\BiodataDosen;
use App\Models\Perkuliahan\BimbingMahasiswa;

class GelarDosen extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function dosen()
    {
        return $this->belongsTo(BiodataDosen::class, 'id_dosen','id_dosen');
    }

    public function bimbing_mahasiswa()
    {
        return $this->belongsTo(BimbingMahasiswa::class, 'id_dosen','id_dosen');
    }
}
