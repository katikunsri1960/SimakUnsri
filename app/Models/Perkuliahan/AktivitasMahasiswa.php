<?php

namespace App\Models\Perkuliahan;

use App\Models\Semester;
use App\Models\ProgramStudi;
use App\Models\SemesterAktif;
use App\Models\AsistensiAkhir;
use Illuminate\Database\Eloquent\Model;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Referensi\JenisAktivitasMahasiswa;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class AktivitasMahasiswa extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $appends = ['id_tanggal_sk_tugas', 'id_tanggal_mulai', 'id_tanggal_selesai'];

    public function asistensi_akhir()
    {
        return $this->hasMany(AsistensiAkhir::class, 'id_aktivitas', 'id_aktivitas');
    }

    public function konversi()
    {
        return $this->belongsTo(MataKuliah::class, 'mk_konversi', 'id_matkul');
    }

    public function nilai_konversi()
    {
        return $this->hasMany(KonversiAktivitas::class, 'id_aktivitas', 'id_aktivitas');
    }

    public function notulensi_sidang()
    {
        return $this->hasMany(NotulensiSidangMahasiswa::class, 'id_aktivitas', 'id_aktivitas');
    }

    public function revisi_sidang()
    {
        return $this->hasMany(RevisiSidangMahasiswa::class, 'id_aktivitas', 'id_aktivitas');
    }

    public function penilaian_sidang()
    {
        return $this->hasMany(NilaiSidangMahasiswa::class, 'id_aktivitas', 'id_aktivitas');
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

    public function aktivitas_pa_prodi($prodi, $semester)
    {
        $data = $this->where('id_prodi', $prodi)
                    ->where('id_semester', $semester)
                    ->where('id_jenis_aktivitas', 7)
                    ->get();

        return $data;
    }

    public function uji_dosen($id_dosen, $semester)
    {
        return $this->with(['uji_mahasiswa', 'uji_mahasiswa.dosen', 'prodi', 'semester', 'jenis_aktivitas_mahasiswa', 'anggota_aktivitas', 'anggota_aktivitas.mahasiswa', 'konversi'])
                    ->where('id_semester', $semester)
                    ->where('approve_sidang', 1)
                    ->whereIn('id_jenis_aktivitas', [1,2,3,4,22])
                    ->whereHas('uji_mahasiswa', function ($query) use ($id_dosen) {
                        $query->whereIn('id_dosen', [$id_dosen])
                              ->whereIn('status_uji_mahasiswa', [1,2,3]);
                        })->withCount([
                            'uji_mahasiswa as count_approved' => function($query) use ($id_dosen) {
                                $query->where('id_dosen', $id_dosen)->where('status_uji_mahasiswa', 1);
                            },
                        ])
                    ->get();
    }

    public function bimbing_ta($id_dosen, $semester)
    {
        // $kategori = [110403,110407,110402,110406,110401,110405];

        return $this->with(['bimbing_mahasiswa', 'anggota_aktivitas_personal', 'prodi'])
                    ->whereHas('bimbing_mahasiswa', function($query) use ($id_dosen) {
                        $query->where('id_dosen', $id_dosen)
                                ->where('approved', 1);
                    })->withCount([
                        'bimbing_mahasiswa as count_approved' => function($query) use ($id_dosen) {
                            $query->where('id_dosen', $id_dosen)->where('approved_dosen', 0);
                        },
                    ])
                    ->where('id_semester', $semester)
                    ->whereIn('id_jenis_aktivitas', [1,2,3,4,22])
                    ->get();
    }

    public function ta($id_prodi, $semester)
    {
        $data = $this->with(['bimbing_mahasiswa', 'anggota_aktivitas_personal', 'prodi', 'konversi' ])
                    ->withCount([
                        'bimbing_mahasiswa as approved' => function($query) {
                            $query->where('approved', 0);
                        },
                        'bimbing_mahasiswa as approved_dosen' => function($query) {
                            $query->where('approved_dosen', 0);
                        },
                        'bimbing_mahasiswa as decline_dosen' => function($query) {
                            $query->where('approved_dosen', 2);
                        },
                    ])
                    ->where('id_prodi', $id_prodi)
                    ->where('id_semester', $semester)
                    ->where('approve_krs', 1)
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

    public function getKrsAkt($id_reg, $id_semester)
    {
        //DATA AKTIVITAS

        $riwayat_pendidikan = RiwayatPendidikan::select('riwayat_pendidikans.*')
                    ->where('id_registrasi_mahasiswa', $id_reg)
                    ->first();

        // $db = new MataKuliah();

        $data_akt = Konversi::
                    where('id_prodi', $riwayat_pendidikan->id_prodi)
                    ->where('id_kurikulum', $riwayat_pendidikan->id_kurikulum)
                    ->whereIn('id_jenis_aktivitas', ['1','2', '3', '4','5','6', '22'])
                    ->get();

        if($data_akt == NULL)
        {
            $mk_akt=NULL;
            $data_akt_ids = NULL;
        }
        else
        {
            $mk_akt = $data_akt;
            $data_akt_ids = $mk_akt->pluck('id_matkul');
        }
        // dd($data_akt);

        // AKTIVITAS MAHASISWA YG DIAMBIL
        $krs_akt = $this::with(['anggota_aktivitas','bimbing_mahasiswa', 'konversi'])
                            // ->whereHas('bimbing_mahasiswa' , function($query) {
                            //     $query->whereNot('id_bimbing_mahasiswa', NUll);
                            // })
                            ->whereHas('anggota_aktivitas' , function($query) use ( $id_reg){
                                $query->where('id_registrasi_mahasiswa', $id_reg);
                            })
                            ->where('id_semester', $id_semester)
                            ->where('id_prodi', $riwayat_pendidikan->id_prodi)
                            ->whereIn('id_jenis_aktivitas', ['1','2', '3', '4','5','6', '22'])
                            ->get();

        // $matkul_konversi = $krs_akt->aktivitas_mahasiswa->konversi;
                        // dd($krs_akt);

            return [$krs_akt, $data_akt_ids, $mk_akt];
    }

    public function aktivitas_non_ta($id_prodi, $semester)
    {
        $data = $this->with(['bimbing_mahasiswa', 'anggota_aktivitas_personal', 'prodi', 'nilai_konversi'])
                    ->withCount([
                        'bimbing_mahasiswa as approved' => function($query) {
                            $query->where('approved', 0);
                        },
                        'bimbing_mahasiswa as approved_dosen' => function($query) {
                            $query->where('approved_dosen', 0);
                        },
                        'bimbing_mahasiswa as decline_dosen' => function($query) {
                            $query->where('approved_dosen', 2);
                        },
                        // Add count for 'nilai_konversi'
                        'nilai_konversi as count_nilai' => function ($query) {
                            $query->select(DB::raw('count(*)')); // This will count all 'nilai_konversi' entries
                        },
                    ])
                    ->where('id_prodi', $id_prodi)
                    ->where('id_semester', $semester)
                    ->where('approve_krs', 1)
                    ->whereIn('id_jenis_aktivitas', [5,6,13,14,15,16,17,18,19,20,21])
                    ->get();

        return $data;
    }

    public function bimbing_non_ta($id_dosen, $semester)
    {
        // $kategori = [110403,110407,110402,110406,110401,110405];

        return $this->with(['bimbing_mahasiswa', 'anggota_aktivitas_personal', 'anggota_aktivitas_personal.mahasiswa', 'prodi', 'konversi'])
                    ->whereHas('bimbing_mahasiswa', function($query) use ($id_dosen) {
                        $query->where('id_dosen', $id_dosen)
                                ->where('approved', 1);
                    })->withCount([
                        'bimbing_mahasiswa as count_approved' => function($query) use ($id_dosen) {
                            $query->where('id_dosen', $id_dosen)->where('approved_dosen', 0);
                        },
                    ])
                    ->where('id_semester', $semester)
                    ->where('approve_krs', 1)
                    ->whereIn('id_jenis_aktivitas', [5,6,13,14,15,16,17,18,19,20,21])
                    ->get();
    }

    public function sidang($id_prodi, $semester)
    {
        $data = $this->with(['uji_mahasiswa', 'bimbing_mahasiswa','anggota_aktivitas_personal', 'prodi', 'konversi', 'nilai_konversi'])
                    ->withCount([
                        'uji_mahasiswa as status_uji' => function($query) {
                            $query->where('status_uji_mahasiswa', 0);
                        },
                        'uji_mahasiswa as approved_prodi' => function($query) {
                            $query->where('status_uji_mahasiswa', 1);
                        },
                        'uji_mahasiswa as decline_dosen' => function($query) {
                            $query->where('status_uji_mahasiswa', 3);
                        },
                    ])
                    ->where('id_prodi', $id_prodi)
                    ->where('approve_sidang', 1)
                    ->where('id_semester', $semester)
                    ->whereIn('id_jenis_aktivitas', [1,2,3,4,22])
                    ->get();

        return $data;
    }

    public function approve_penguji($id_aktivitas)
    {
        $data = $this->where('id_aktivitas', $id_aktivitas)->first();

        if(is_null($data->sk_tugas)){
            return redirect()->back()->with('error', 'SK Tugas Aktivitas Harus Di Isi.');
        }

        if(is_null($data->jadwal_ujian)){
            return redirect()->back()->with('error', 'Jadwal Ujian Belum di Atur.');
        }else{
            $data->uji_mahasiswa()->where('status_uji_mahasiswa', '!=', '2')->update(['status_uji_mahasiswa' => 1]);
        }

        return $data;
    }
}
