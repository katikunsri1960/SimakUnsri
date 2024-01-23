<?php

namespace App\Models\Perkuliahan;

use App\Models\Referensi\Pembiayaan;
use App\Models\StatusMahasiswa;
use App\Models\Semester;
use App\Models\ProgramStudi;
use App\Models\Mahasiswa\RiwayatPendidikan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AktivitasKuliahMahasiswa extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function riwayat_pendidikan()
    {
        return $this->belongsTo(RiwayatPendidikan::class, 'id_registrasi_mahasiswa', 'id_registrasi_mahasiswa');
    }

    public function prodi()
    {
        return $this->belongsTo(ProgramStudi::class, 'id_prodi', 'id_prodi');
    }

    public function periode_masuk()
    {
        return $this->belongsTo(Semester::class, 'id_periode_masuk', 'id_semester');
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'id_semester', 'id_semester');
    }

    public function status_mahasiswa()
    {
        return $this->belongsTo(StatusMahasiswa::class, 'id_status_mahasiswa', 'id_status_mahasiswa');
    }

    public function pembiayaan()
    {
        return $this->belongsTo(Pembiayaan::class, 'id_pembiayaan', 'id_pembiayaan');
    }
}
