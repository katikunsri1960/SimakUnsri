<?php

namespace App\Models\Mahasiswa;

use App\Models\Dosen\BiodataDosen;
use App\Models\Semester;
use App\Models\Perkuliahan\AktivitasKuliahMahasiswa;
use App\Models\JalurMasuk;
use App\Models\Perkuliahan\AktivitasMahasiswa;
use App\Models\Perkuliahan\AnggotaAktivitasMahasiswa;
use App\Models\Perkuliahan\ListKurikulum;
use App\Models\Perkuliahan\PesertaKelasKuliah;
use App\Models\Perkuliahan\TranskripMahasiswa;
use App\Models\ProgramStudi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class RiwayatPendidikan extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $appends = ['angkatan', 'gelombang_masuk'];

    public function anggota_aktivitas_mahasiswa()
    {
        return $this->hasMany(AnggotaAktivitasMahasiswa::class, 'id_registrasi_mahasiswa', 'id_registrasi_mahasiswa');
    }

    public function aktivitas_mahasiswa()
    {
        return $this->hasManyThrough(AktivitasMahasiswa::class, AnggotaAktivitasMahasiswa::class, 'id_registrasi_mahasiswa', 'id_aktivitas', 'id_registrasi_mahasiswa', 'id_aktivitas');
    }

    public function dosen_pa()
    {
        return $this->belongsTo(BiodataDosen::class, 'dosen_pa', 'id_dosen');
    }

    public function peserta_kelas()
    {
        return $this->hasMany(PesertaKelasKuliah::class, 'id_registrasi_mahasiswa', 'id_registrasi_mahasiswa');
    }

    public function pembimbing_akademik()
    {
        return $this->belongsTo(BiodataDosen::class, 'dosen_pa', 'id_dosen');
    }

    public function kurikulum()
    {
        return $this->belongsTo(ListKurikulum::class, 'id_kurikulum', 'id_kurikulum');
    }

    public function biodata()
    {
        return $this->belongsTo(BiodataMahasiswa::class, 'id_mahasiswa', 'id_mahasiswa');
    }

    public function prodi()
    {
        return $this->belongsTo(ProgramStudi::class, 'id_prodi', 'id_prodi');
    }

    public function periode_masuk()
    {
        return $this->belongsTo(Semester::class, 'id_periode_masuk', 'id_semester');
    }

    public function getAngkatanAttribute()
    {
        return substr($this->id_periode_masuk, 0, 4);
    }

    public function getGelombangMasukAttribute()
    {
        return substr($this->id_periode_masuk, 4, 1);
    }

    public function jalur_masuk()
    {
        return $this->belongsTo(JalurMasuk::class, 'id_jalur_daftar', 'id_jalur_masuk');
    }

    public function aktivitas_kuliah()
    {
        return $this->hasMany(AktivitasKuliahMahasiswa::class, 'id_registrasi_mahasiswa', 'id_registrasi_mahasiswa');
    }

    public function mahasiswaProdi($id_prodi)
    {
        return $this->where('id_prodi', $id_prodi);
    }

    public function transkrip_mahasiswa()
    {
        return $this->hasMany(TranskripMahasiswa::class, 'id_registrasi_mahasiswa', 'id_registrasi_mahasiswa');
    }

    public function set_kurikulum_angkatan($tahun_angkatan, $id_kurikulum, $prodi)
    {
        try {
            DB::beginTransaction();
            $this->where(DB::raw('LEFT(id_periode_masuk, 4)'), $tahun_angkatan)->where('id_prodi', $prodi)
                ->update(['id_kurikulum' => $id_kurikulum]);

            DB::commit();

            $result = [
                'status' => 'success',
                'message' => 'Kurikulum berhasil diubah'
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            $result = [
                'status' => 'error',
                'message' => 'Kurikulum gagal diubah'
            ];

            return $result;
        }

        return $result;
    }

    public function detail_isi_krs($id_prodi, $semesterAktif)
    {
        $data = $this->with('pembimbing_akademik')->where('id_prodi', $id_prodi)
                ->whereNull('id_jenis_keluar')
                ->where(function ($query) use ($semesterAktif) {
                    $query->whereExists(function ($subquery) use ($semesterAktif) {
                        $subquery->select(DB::raw(1))
                            ->from('peserta_kelas_kuliahs as p')
                            ->join('kelas_kuliahs as k', 'p.id_kelas_kuliah', '=', 'k.id_kelas_kuliah')
                            ->where('k.id_semester', $semesterAktif)
                            ->whereColumn('p.id_registrasi_mahasiswa', 'riwayat_pendidikans.id_registrasi_mahasiswa');
                    })
                    ->orWhere(function ($query) use ($semesterAktif) {
                        $query->whereNotExists(function ($subquery) use ($semesterAktif) {
                            $subquery->select(DB::raw(1))
                                ->from('peserta_kelas_kuliahs as p')
                                ->join('kelas_kuliahs as k', 'p.id_kelas_kuliah', '=', 'k.id_kelas_kuliah')
                                ->where('k.id_semester', $semesterAktif)
                                ->whereColumn('p.id_registrasi_mahasiswa', 'riwayat_pendidikans.id_registrasi_mahasiswa');
                        })
                        ->whereExists(function ($subquery) use ($semesterAktif) {
                            $subquery->select(DB::raw(1))
                                ->from('anggota_aktivitas_mahasiswas as aam')
                                ->join('aktivitas_mahasiswas as a', 'aam.id_aktivitas', '=', 'a.id_aktivitas')
                                ->where('a.id_semester', $semesterAktif)
                                ->whereIn('a.id_jenis_aktivitas', [1,2,3,4,5,6,13,14,15,16,17,18,19,20,21,22])
                                ->whereColumn('aam.id_registrasi_mahasiswa', 'riwayat_pendidikans.id_registrasi_mahasiswa');
                        });
                    });
                })
                ->distinct()
                ->get();

        return $data;
    }

    public function krs_data($id_prodi, $semesterAktif, $isApproved)
    {
        $data = $this->where('id_prodi', $id_prodi)
                ->whereNull('id_jenis_keluar')
                ->where(function ($query) use ($semesterAktif, $isApproved) {
                    $query->whereExists(function ($subquery) use ($semesterAktif, $isApproved) {
                        $subquery->select(DB::raw(1))
                            ->from('peserta_kelas_kuliahs as p')
                            ->join('kelas_kuliahs as k', 'p.id_kelas_kuliah', '=', 'k.id_kelas_kuliah')
                            ->where('k.id_semester', $semesterAktif)
                            ->where('p.approved', $isApproved)
                            ->whereColumn('p.id_registrasi_mahasiswa', 'riwayat_pendidikans.id_registrasi_mahasiswa');
                    })
                    ->orWhere(function ($query) use ($semesterAktif, $isApproved) {
                        $query->whereNotExists(function ($subquery) use ($semesterAktif, $isApproved) {
                            $subquery->select(DB::raw(1))
                                ->from('peserta_kelas_kuliahs as p')
                                ->join('kelas_kuliahs as k', 'p.id_kelas_kuliah', '=', 'k.id_kelas_kuliah')
                                ->where('k.id_semester', $semesterAktif)
                                ->where('p.approved', $isApproved)
                                ->whereColumn('p.id_registrasi_mahasiswa', 'riwayat_pendidikans.id_registrasi_mahasiswa');
                        })
                        ->whereExists(function ($subquery) use ($semesterAktif, $isApproved) {
                            $subquery->select(DB::raw(1))
                                ->from('anggota_aktivitas_mahasiswas as aam')
                                ->join('aktivitas_mahasiswas as a', 'aam.id_aktivitas', '=', 'a.id_aktivitas')
                                ->where('a.id_semester', $semesterAktif)
                                ->where('a.approve_krs', $isApproved)
                                ->whereIn('a.id_jenis_aktivitas', [1,2,3,4,5,6,13,14,15,16,17,18,19,20,21,22])
                                ->whereColumn('aam.id_registrasi_mahasiswa', 'riwayat_pendidikans.id_registrasi_mahasiswa');
                        });
                    });
                })
                ->distinct()
                ->get();

        return $data;
    }

}
