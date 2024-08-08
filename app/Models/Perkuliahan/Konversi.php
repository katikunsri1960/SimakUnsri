<?php

namespace App\Models\Perkuliahan;

use App\Models\Semester;
use Illuminate\Database\Eloquent\Model;
use App\Models\Perkuliahan\ListKurikulum;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Konversi extends Model
{
    use HasFactory;
    protected $table = 'aktivitas_konversi';
    protected $guarded = [];

    // public function kurikulum()
    // {
    //     return $this->belongsTo(ListKurikulum::class, 'id_kurikulum', 'id_kurikulum');
    // }

    // public function matkul_kurikulum()
    // {
    //     return $this->belongsTo(MatkulKurikulum::class, 'id_matkul', 'id_matkul');
    // }

    // public function matkul()
    // {
    //     return $this->belongsTo(MataKuliah::class, 'id_matkul', 'id_matkul');
    // }

    // public function semester()
    // {
    //     return $this->belongsTo(Semester::class, 'id_semester', 'id_semester');
    // }
}
