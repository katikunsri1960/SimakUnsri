<?php

namespace App\Models\Perkuliahan;

use App\Models\Semester;
use App\Models\ProgramStudi;
use App\Models\Referensi\JenisAktivitasMahasiswa;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class AktivitasMahasiswa extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function jenis_aktivitas_mahasiswa()
    {
        return $this->belongsTo(JenisAktivitasMahasiswa::class, 'id_jenis_aktivitas', 'id_jenis_aktivitas_mahasiswa');
    }

    public function prodi()
    {
        return $this->belongsTo(ProgramStudi::class, 'id_prodi', 'id_prodi');
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'id_semester', 'id_semester');
    }


}
