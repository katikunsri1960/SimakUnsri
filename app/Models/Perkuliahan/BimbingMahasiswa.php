<?php

namespace App\Models\Perkuliahan;

use App\Models\Dosen\BiodataDosen;
use App\Models\Dosen\GelarDosen;
use App\Models\Referensi\KategoriKegiatan;
use App\Models\SemesterAktif;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    public function gelar()
    {
        return $this->belongsTo(GelarDosen::class, 'id_dosen','id_dosen');
    }

    public function aktivitas_pa_prodi($prodi, $semester)
    {
        $data = $this->join('aktivitas_mahasiswas as am', 'am.id_aktivitas', 'bimbing_mahasiswas.id_aktivitas')
                    ->where('am.id_prodi', $prodi)
                    ->where('am.id_semester', $semester)
                    ->where('am.id_jenis_aktivitas', 7)
                    ->select(
                        'am.id as id',
                        'am.sk_tugas as sk_tugas',
                        'am.tanggal_sk_tugas',
                        'bimbing_mahasiswas.nidn',
                        'bimbing_mahasiswas.nama_dosen',
                        'bimbing_mahasiswas.id_dosen',
                        'bimbing_mahasiswas.id_kategori_kegiatan',
                        DB::raw('(SELECT COUNT(*) FROM anggota_aktivitas_mahasiswas WHERE id_aktivitas = am.id_aktivitas) as jumlah_anggota')
                    )
                    ->get();

        return $data;
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
