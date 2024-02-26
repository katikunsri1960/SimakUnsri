<?php

namespace App\Models\Perkuliahan;

use App\Models\Semester;
use App\Models\ProgramStudi;
use App\Models\SemesterAktif;
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

    public function uji_mahasiswa()
    {
        return $this->hasMany(UjiMahasiswa::class, 'id_aktivitas', 'id_aktivitas')->orderBy('id_kategori_kegiatan');
    }

    public function anggota_aktivitas()
    {
        return $this->hasMany(AnggotaAktivitasMahasiswa::class, 'id_aktivitas', 'id_aktivitas');
    }

    public function prodi()
    {
        return $this->belongsTo(ProgramStudi::class, 'id_prodi', 'id_prodi');
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'id_semester', 'id_semester');
    }

    public function uji_dosen($id_dosen)
    {
        $id_semester = SemesterAktif::where('id',1)->pluck('id_semester')->first() ?? (date('m') >= 8 ? (date('Y').'1') : (date('Y')-1).'2');

        return $this->with(['uji_mahasiswa', 'uji_mahasiswa.dosen', 'prodi', 'semester', 'jenis_aktivitas_mahasiswa', 'anggota_aktivitas', 'anggota_aktivitas.mahasiswa'])
                    ->where('id_semester', $id_semester)
                    ->whereIn('id_jenis_aktivitas', [1,2,3,4,22])
                    ->whereHas('uji_mahasiswa', function ($query) use ($id_dosen) {
                        $query->whereIn('id_dosen', [$id_dosen]);
                    })->get();
    }


}
