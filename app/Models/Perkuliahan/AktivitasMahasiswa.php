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
    protected $appends = ['id_tanggal_sk_tugas', 'id_tanggal_mulai', 'id_tanggal_selesai'];

    public function konversi()
    {
        return $this->belongsTo(MataKuliah::class, 'mk_konversi', 'id_matkul');
    }

    public function getIdTanggalSkTugasAttribute()
    {
        return date('d-m-Y', strtotime($this->tanggal_sk_tugas));
    }

    public function getIdTanggalMulaiAttribute()
    {
        return date('d-m-Y', strtotime($this->tanggal_mulai));
    }

    public function getIdTanggalSelesaiAttribute()
    {
        return date('d-m-Y', strtotime($this->tanggal_selesai));
    }

    public function jenis_aktivitas_mahasiswa()
    {
        return $this->belongsTo(JenisAktivitasMahasiswa::class, 'id_jenis_aktivitas', 'id_jenis_aktivitas_mahasiswa');
    }

    public function uji_mahasiswa()
    {
        return $this->hasMany(UjiMahasiswa::class, 'id_aktivitas', 'id_aktivitas')->orderBy('id_kategori_kegiatan');
    }

    public function bimbing_mahasiswa()
    {
        return $this->hasMany(BimbingMahasiswa::class, 'id_aktivitas', 'id_aktivitas')->orderBy('id_kategori_kegiatan');
    }

    public function anggota_aktivitas()
    {
        return $this->hasMany(AnggotaAktivitasMahasiswa::class, 'id_aktivitas', 'id_aktivitas');
    }

    public function anggota_aktivitas_personal()
    {
        return $this->hasOne(AnggotaAktivitasMahasiswa::class, 'id_aktivitas', 'id_aktivitas');
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

    public function bimbing_ta($id_dosen, $semester)
    {
        // $kategori = [110403,110407,110402,110406,110401,110405];

        return $this->with(['bimbing_mahasiswa', 'anggota_aktivitas_personal', 'prodi'])
                    ->whereHas('bimbing_mahasiswa', function($query) use ($id_dosen) {
                        $query->where('id_dosen', $id_dosen)
                                ->where('approved', 1);
                    })->withCount([
                        'bimbing_mahasiswa as approved' => function($query) use ($id_dosen) {
                            $query->where('id_dosen', $id_dosen)->where('approved_dosen', 0);
                        },
                    ])
                    ->where('id_semester', $semester)
                    ->whereIn('id_jenis_aktivitas', [1,2,3,4,22])
                    ->get();
    }

    public function ta($id_prodi, $semester)
    {
        $data = $this->with(['bimbing_mahasiswa', 'anggota_aktivitas_personal', 'prodi'])
                    ->withCount([
                        'bimbing_mahasiswa as approved' => function($query) {
                            $query->where('approved', 0);
                        },
                        'bimbing_mahasiswa as approved_dosen' => function($query) {
                            $query->where('approved_dosen', 0);
                        },
                    ])
                    ->where('id_prodi', $id_prodi)
                    ->where('id_semester', $semester)
                    ->whereIn('id_jenis_aktivitas', [1,2,3,4,22])
                    ->get();

        return $data;
    }

    public function approve_pembimbing($id_aktivitas)
    {
        $data = $this->where('id_aktivitas', $id_aktivitas)->first();
        $data->bimbing_mahasiswa()->update(['approved' => 1]);

        return $data;
    }


}
