<?php

namespace App\Models\Perkuliahan;

use App\Models\KuisonerAnswer;
use App\Models\Semester;
use App\Models\Perkuliahan\MataKuliah;
use App\Models\ProgramStudi;
use App\Models\RuangPerkuliahan;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelasKuliah extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = ['kuisoner_count', 'id_tanggal_mulai_efektif', 'id_tanggal_akhir_efektif'];

    public function getIdTanggalMulaiEfektifAttribute()
    {
        return Carbon::parse($this->tanggal_mulai_efektif)->format('d-m-Y');
    }

    public function getIdTanggalAkhirEfektifAttribute()
    {
        return Carbon::parse($this->tanggal_akhir_efektif)->format('d-m-Y');
    }

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
        return $this->hasMany(DosenPengajarKelasKuliah::class, 'id_kelas_kuliah', 'id_kelas_kuliah')->orderBy('urutan', 'asc');
    }

    public function peserta_kelas()
    {
        return $this->hasMany(PesertaKelasKuliah::class, 'id_kelas_kuliah', 'id_kelas_kuliah');
    }

    public function peserta_kelas_approved()
    {
        return $this->hasMany(PesertaKelasKuliah::class, 'id_kelas_kuliah', 'id_kelas_kuliah')->where('approved', 1);
    }

    public function nilai_perkuliahan()
    {
        return $this->hasMany(NilaiPerkuliahan::class, 'id_kelas_kuliah', 'id_kelas_kuliah');
    }

    public function nilai_komponen()
    {
        return $this->hasMany(NilaiKomponenEvaluasi::class, 'id_kelas', 'id_kelas_kuliah');
    }

    public function ruang_perkuliahan()
    {
        return $this->belongsTo(RuangPerkuliahan::class, 'ruang_perkuliahan_id', 'id');
    }

    public function ruang_ujian()
    {
        return $this->belongsTo(RuangPerkuliahan::class, 'lokasi_ujian_id', 'id');
    }

    public function detail_penilaian_perkuliahan(string $kelas)
    {
        $data = $this->with([
            'peserta_kelas' => function ($query) {
                $query->where('approved', 1);
            },
            'nilai_perkuliahan',
            'nilai_komponen' => function($query) {
                $query->orderBy('urutan');
            },
        ])
        ->where('id_kelas_kuliah', $kelas)
        ->first();

        // dd($data);
        return $data;
    }

    public function kuisoner()
    {
        return $this->hasMany(KuisonerAnswer::class, 'id_kelas_kuliah', 'id_kelas_kuliah');
    }

    public function getKuisonerCountAttribute()
    {
        return $this->kuisoner->count();
    }
}
