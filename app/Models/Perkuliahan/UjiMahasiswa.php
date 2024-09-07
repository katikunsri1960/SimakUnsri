<?php

namespace App\Models\Perkuliahan;

use App\Models\Dosen\BiodataDosen;
use App\Models\SemesterAktif;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Referensi\KategoriKegiatan;

class UjiMahasiswa extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function aktivitas_mahasiswa()
    {
        return $this->belongsTo(AktivitasMahasiswa::class, 'id_aktivitas', 'id_aktivitas');
    }

    public function anggota_aktivitas_personal()
    {
        return $this->hasOne(AnggotaAktivitasMahasiswa::class, 'id_aktivitas', 'id_aktivitas');
    }

    public function kategori_kegiatan()
    {
        return $this->belongsTo(KategoriKegiatan::class, 'id_kategori_kegiatan', 'id_kategori_kegiatan');
    }

    public function dosen()
    {
        return $this->belongsTo(BiodataDosen::class, 'id_dosen', 'id_dosen');
    }

    public function uji_dosen_semester($id_dosen)
    {
        $id_semester = SemesterAktif::where('id',1)->pluck('id_semester')->first() ?? (date('m') >= 8 ? (date('Y').'1') : (date('Y')-1).'2');

        return $this->with('aktivitas_mahasiswa')
                    ->where('id_dosen', $id_dosen)
                    ->whereHas('aktivitas_mahasiswa', function ($query) use ($id_semester) {
                        $query->where('id_semester', $id_semester);
                    })->get();
    }
}
