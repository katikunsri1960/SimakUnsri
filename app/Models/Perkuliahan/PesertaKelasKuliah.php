<?php

namespace App\Models\Perkuliahan;

use App\Models\Semester;
use App\Models\Perkuliahan\MataKuliah;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PesertaKelasKuliah extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function kelas_kuliah()
    {
        return $this->belongsTo(KelasKuliah::class, 'id_kelas_kuliah', 'id_kelas_kuliah');
    }

    public function matkul()
    {
        $this->belongsTo(MataKuliah::class, 'id_matkul', 'id_matkul');
    }

    public function nilai_perkuliahan()
    {
        return $this->hasMany(NilaiPerkuliahan::class, 'id_kelas_kuliah', 'id_kelas_kuliah');
    }
}
