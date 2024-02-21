<?php

namespace App\Models\Perkuliahan;

use App\Models\Semester;
use App\Models\ProgramStudi;
use App\Models\Dosen\BiodataDosen as Dosen;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DosenPengajarKelasKuliah extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'id_dosen', 'id_dosen');
    }

    public function kelas_kuliah()
    {
        return $this->belongsTo(KelasKuliah::class, 'id_kelas_kuliah', 'id_kelas_kuliah');
    }

    public function prodi()
    {
        return $this->belongsTo(ProgramStudi::class, 'id_prodi', 'id_prodi');
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'id_semester', 'id_semester');
    }
    
    public function peserta_kelas()
    {
        return $this->hasManyThrough(PesertaKelasKuliah::class, KelasKuliah::class, 'id_kelas_kuliah', 'id_kelas_kuliah', 'id_kelas_kuliah', 'id_kelas_kuliah');
    }
}
