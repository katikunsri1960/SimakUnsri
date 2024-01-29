<?php

namespace App\Models\Perkuliahan;

use App\Models\ProgramStudi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function matkul_kurikulum()
    {
        return $this->hasMany(MatkulKurikulum::class, 'id_matkul', 'id_matkul');
    }

    public function prodi()
    {
        return $this->belongsTo(ProgramStudi::class, 'id_prodi', 'id_prodi');
    }

    public function kelas_kuliah()
    {
        return $this->belongsTo(KelasKuliah::class, 'id_matkul', 'id_matkul');
    }


}
