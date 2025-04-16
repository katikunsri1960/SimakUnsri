<?php

namespace App\Models\Dosen;

use App\Models\Perkuliahan\DosenPengajarKelasKuliah;
use App\Models\Perkuliahan\UjiMahasiswa;
use App\Models\Semester;
use App\Models\SemesterAktif;
use App\Models\Wilayah;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BiodataDosen extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class, 'id_wilayah', 'id_wilayah');
    }

    public function penugasan()
    {
        return $this->hasMany(PenugasanDosen::class, 'id_dosen', 'id_dosen');
    }

    public function getJenisKelaminAttribute($value)
    {
        switch ($value) {
            case 'L':
                return 'Laki-laki';
            case 'P':
                return 'Perempuan';
            default:
                return 'Lainnya';
        }
    }

    public function getIdTanggalLahirAttribute()
    {
        return date('d-m-Y', strtotime($this->tanggal_lahir));
    }

    public function list_dosen($tahun_ajaran = null)
    {
        $tahun_ajaran = $tahun_ajaran ?? (date('m') >= 8 ? date('Y') : date('Y') - 1);

        return $this->leftJoin('penugasan_dosens as p', 'p.id_dosen', '=', 'biodata_dosens.id_dosen')
            ->select('biodata_dosens.*', 'p.nama_program_studi as prodi', 'p.a_sp_homebase as homebase')
            ->where('id_jenis_sdm', 12)
            ->where('p.id_tahun_ajaran', $tahun_ajaran)
            ->get();
    }

    public function list_dosen_prodi($tahun_ajaran, $id_prodi)
    {

        $tahun_ajaran = $tahun_ajaran ?? SemesterAktif::where('id', 1)->first()->semester->id_tahun_ajaran ?? (date('m') >= 8 ? date('Y') : date('Y') - 1);

        return $this->leftJoin('penugasan_dosens as p', 'p.id_dosen', '=', 'biodata_dosens.id_dosen')
            ->select('biodata_dosens.*', 'p.nama_program_studi as prodi', 'p.a_sp_homebase as homebase')
            ->where('id_jenis_sdm', 12)
            ->where('p.id_tahun_ajaran', $tahun_ajaran)
            ->where('p.id_prodi', $id_prodi)
            ->get();
    }

    public function data_dosen($id_dosen)
    {
        $tahun_ajaran = SemesterAktif::join('semesters', 'semester_aktifs.id_semester', 'semesters.id_semester')->pluck('id_tahun_ajaran')->first() ?? (date('m') >= 8 ? date('Y') : date('Y') - 1);

        return $this->leftJoin('penugasan_dosens as p', 'biodata_dosens.id_dosen', 'p.id_dosen')
            ->where('p.id_tahun_ajaran', $tahun_ajaran)
            ->where('biodata_dosens.id_dosen', $id_dosen)->first();

    }

    public function dosen_pengajar_kelas($id_dosen)
    {
        $id_semester = SemesterAktif::where('id', 1)->pluck('id_semester')->first() ?? (date('m') >= 8 ? (date('Y').'1') : (date('Y') - 1).'2');
        $tahun_ajaran = SemesterAktif::join('semesters', 'semester_aktifs.id_semester', 'semesters.id_semester')->pluck('id_tahun_ajaran')->first() ?? (date('m') >= 8 ? date('Y') : date('Y') - 1);

        $id_registrasi_dosen = $this->leftJoin('penugasan_dosens as p', 'biodata_dosens.id_dosen', 'p.id_dosen')
            ->where('p.id_tahun_ajaran', $tahun_ajaran)
            ->where('biodata_dosens.id_dosen', $id_dosen)->get()->pluck('id_registrasi_dosen');

        $data = DosenPengajarKelasKuliah::with([
            'kelas_kuliah',
            'kelas_kuliah.matkul',
            'kelas_kuliah.prodi',
            'kelas_kuliah.dosen_pengajar',
            'kelas_kuliah.peserta_kelas_approved',
            'kelas_kuliah.nilai_perkuliahan',
            'kelas_kuliah.dosen_pengajar.dosen',
        ])
            ->whereIn('id_registrasi_dosen', $id_registrasi_dosen)
            ->where('id_semester', $id_semester)
            ->get();

        return $data;
    }

    public function riwayat_kelas($id_dosen, $semester)
    {

        $tahun_ajaran = Semester::where('id_semester', $semester)->pluck('id_tahun_ajaran')->first();

        $id_registrasi_dosen = $this->leftJoin('penugasan_dosens as p', 'biodata_dosens.id_dosen', 'p.id_dosen')
            ->where('p.id_tahun_ajaran', $tahun_ajaran)
            ->where('biodata_dosens.id_dosen', $id_dosen)->get()->pluck('id_registrasi_dosen');

        $data = DosenPengajarKelasKuliah::with([
            'kelas_kuliah',
            'kelas_kuliah.matkul',
            'kelas_kuliah.prodi',
            'kelas_kuliah.dosen_pengajar',
            'kelas_kuliah.peserta_kelas_approved',
            'kelas_kuliah.nilai_perkuliahan',
            'kelas_kuliah.dosen_pengajar.dosen',
        ])
            ->whereIn('id_registrasi_dosen', $id_registrasi_dosen)
            ->where('id_semester', $semester)
            ->get();

        return $data;
    }

    public function uji_aktivitas($id_dosen)
    {
        $db = new UjiMahasiswa;

    }
}
