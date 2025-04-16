<?php

namespace App\Models\Perkuliahan;

use App\Models\JenisEvaluasi;
use App\Models\ProgramStudi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RencanaEvaluasi extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function jenis_evaluasi()
    {
        return $this->belongsTo(JenisEvaluasi::class, 'id_jenis_evaluasi', 'id_jenis_evaluasi');
    }

    public function mata_kuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'id_matkul', 'id_matkul');
    }

    public function prodi()
    {
        return $this->belongsTo(ProgramStudi::class, 'id_prodi', 'id_prodi');
    }
}
