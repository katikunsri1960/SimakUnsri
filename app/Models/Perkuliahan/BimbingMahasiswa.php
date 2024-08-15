<?php

namespace App\Models\Perkuliahan;

use App\Models\Dosen\BiodataDosen;
use App\Models\Referensi\KategoriKegiatan;
use App\Models\SemesterAktif;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BimbingMahasiswa extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function aktivitas_mahasiswa()
    {
        return $this->belongsTo(AktivitasMahasiswa::class, 'id_aktivitas', 'id_aktivitas');
    }

    public function anggota_aktivitas()
    {
        return $this->hasManyThrough(AnggotaAktivitasMahasiswa::class, AktivitasMahasiswa::class, 'id_aktivitas', 'id_aktivitas', 'id_aktivitas', 'id_aktivitas');
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

    public function bimbing_ta($id_dosen, $semester)
    {
        $kategori = [110403,110407,110402,110406,110401,110405];

        return $this->with(['aktivitas_mahasiswa', 'dosen', 'anggota_aktivitas'])
                    ->whereHas('aktivitas_mahasiswa', function($query) use ($semester) {
                        $query->where('id_semester', $semester);
                    })
                    ->where('id_dosen', $id_dosen)
                    ->whereIn('id_kategori_kegiatan', $kategori)
                    ->get();
    }
}
