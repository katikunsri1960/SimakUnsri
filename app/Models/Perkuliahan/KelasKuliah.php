<?php

namespace App\Models\Perkuliahan;

use App\Models\Semester;
use App\Models\Perkuliahan\MataKuliah;
use App\Models\ProgramStudi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelasKuliah extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'id_semester', 'id_semester');
    }

    public function matkul()
    {
        return $this->belongsTo(MataKuliah::class, 'id_matkul', 'id_matkul');
    }

    public function prodi()
    {
        return $this->belongsTo(ProgramStudi::class, 'id_prodi', 'id_prodi');
    }

    // public function ruang_perkuliahan()
    // {
    //     return $this->belongsTo(RuangPerkuliahan::class, 'id_prodi', 'ruang_perkuliahan_id');
    // }

    public function dosen_pengajar()
    {
        return $this->hasMany(DosenPengajarKelasKuliah::class, 'id_kelas_kuliah', 'id_kelas_kuliah');
    }

    public function peserta_kelas()
    {
        return $this->hasMany(PesertaKelasKuliah::class, 'id_kelas_kuliah', 'id_kelas_kuliah');
    }

    public function nilai_perkuliahan()
    {
        return $this->hasMany(NilaiPerkuliahan::class, 'id_kelas_kuliah', 'id_kelas_kuliah');
    }

    public function detail_penilaian_perkuliahan(string $kelas)
    {
        $db = new KelasKuliah;
        $data = $db->with('peserta_kelas', 'nilai_perkuliahan')->where('id_kelas_kuliah', $kelas)->first();

        return $data;
    }
}
