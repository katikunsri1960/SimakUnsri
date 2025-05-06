<?php

namespace App\Models;

use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Perkuliahan\AktivitasKuliahMahasiswa;
use App\Models\Perkuliahan\KelasKuliah;
use App\Models\Perkuliahan\PesertaKelasKuliah;
use App\Models\Referensi\GelarLulusan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramStudi extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'id_jurusan', 'jurusan_id');
    }

    public function mahasiswa()
    {
        return $this->hasMany(RiwayatPendidikan::class, 'id_prodi', 'id_prodi');
    }

    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class, 'fakultas_id', 'id');
    }

    public function peserta_kelas()
    {
        return $this->hasManyThrough(PesertaKelasKuliah::class, KelasKuliah::class, 'id_prodi', 'id_kelas_kuliah', 'id_prodi', 'id_kelas_kuliah');
    }

    public function gelar_lulusan()
    {
        return $this->hasOne(GelarLulusan::class, 'id_prodi', 'id_prodi');
    }

    public function aktivitas_kuliah()
    {
        return $this->hasMany(AktivitasKuliahMahasiswa::class, 'id_prodi', 'id_prodi');
    }
}
