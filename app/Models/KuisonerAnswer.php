<?php

namespace App\Models;

use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Perkuliahan\KelasKuliah;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KuisonerAnswer extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function kuisoner_question()
    {
        return $this->belongsTo(KuisonerQuestion::class, 'kuisoner_question_id', 'id');
    }

    public function kelas_kuliah()
    {
        return $this->belongsTo(KelasKuliah::class, 'id_kelas_kuliah', 'id_kelas_kuliah');
    }

    public function riwayat_pendidikan()
    {
        return $this->belongsTo(RiwayatPendidikan::class, 'id_registrasi_mahasiswa', 'id_registrasi_mahasiswa');
    }
}
