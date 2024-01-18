<?php

namespace App\Models\Mahasiswa;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProgramStudi;
use App\Models\Semester;

class RiwayatPendidikan extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function biodata()
    {
        return $this->belongsTo(BiodataMahasiswa::class, 'id_mahasiswa', 'id_mahasiswa');
    }

    public function prodi()
    {
        return $this->belongsTo(ProgramStudi::class, 'id_prodi', 'id_prodi');
    }

    public function periode_masuk()
    {
        return $this->belongsTo(Semester::class, 'id_periode_masuk', 'id_semester');
    }

    public function getAngkatanAttribute()
    {
        return substr($this->id_periode_masuk, 0, 4);
    }

    public function getGelombangMasukAttribute()
    {
        return substr($this->id_periode_masuk, 4, 1);
    }

    public function getIdJalurMasukAttribute()
    {
        return substr($this->nim, 5, 1);
    }
}
