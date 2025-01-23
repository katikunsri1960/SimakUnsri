<?php

namespace App\Models\Perkuliahan;

use App\Models\Semester;
use App\Models\ProgramStudi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NilaiPerkuliahan extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function prodi()
    {
        return $this->belongsTo(ProgramStudi::class, 'id_prodi', 'id_prodi');
    }

    public function dosen_pengajar()
    {
        return $this->hasMany(DosenPengajarKelasKuliah::class, 'id_kelas_kuliah', 'id_kelas_kuliah');
    }

    public function nilai_komponen()
    {
        return $this->hasMany(NilaiKomponenEvaluasi::class, 'id_kelas', 'id_kelas_kuliah');
    }

    public function kelas_kuliah()
    {
        return $this->belongsTo(KelasKuliah::class, 'id_kelas_kuliah', 'id_kelas_kuliah');
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'id_semester', 'id_semester');
    }
}
