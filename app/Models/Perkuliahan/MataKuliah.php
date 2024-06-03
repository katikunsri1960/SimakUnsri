<?php

namespace App\Models\Perkuliahan;

use App\Models\ProgramStudi;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\Mahasiswa\RiwayatPendidikan;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use function PHPUnit\Framework\isEmpty;

class MataKuliah extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function prasyarat_matkul()
    {
        return $this->hasMany(PrasyaratMatkul::class, 'id_matkul', 'id_matkul');
    }

    public function prodi()
    {
        return $this->belongsTo(ProgramStudi::class, 'id_prodi', 'id_prodi');
    }

    public function rencana_pembelajaran()
    {
        return $this->hasMany(RencanaPembelajaran::class, 'id_matkul', 'id_matkul');
    }

    public function kelas_kuliah()
    {
        return $this->hasMany(KelasKuliah::class, 'id_matkul', 'id_matkul');
    }

    public function kurikulum()
    {
        return $this->hasOneThrough(
            ListKurikulum::class,
            MatkulKurikulum::class,
            'id_matkul', // Foreign key on MatkulKurikulum table...
            'id_kurikulum', // Foreign key on Kurikulum table...
            'id_matkul', // Local key on MataKuliah table...
            'id_kurikulum' // Local key on MatkulKurikulum table...
        );
    }

    public function matkul_prodi($id_prodi)
    {

        $kurikulum = ListKurikulum::where('id_prodi', $id_prodi)
                ->where('is_active', 1)
                ->pluck('id_kurikulum');

        $result = $this->with(['kurikulum'])->where('id_prodi', $id_prodi)
            ->whereHas('kurikulum', function($query) use ($kurikulum){
                $query->whereIn('list_kurikulums.id_kurikulum', $kurikulum);
            })
            ->orderBy('kode_mata_kuliah')
            ->get();

        // $result = $this->where('id_prodi', auth()->user()->fk_id)->orderBy('kode_mata_kuliah')->get();

        return $result;
    }

    public function getSksMax($id_reg, $semester_aktif)
    {
        $ips = AktivitasKuliahMahasiswa::select('ips')
                    ->where('id_registrasi_mahasiswa', $id_reg)
                    ->where('id_semester', $semester_aktif->id_semester)
                    // ->where('id_status_mahasiswa', ['O'])
                    ->orderBy('id_semester', 'DESC')
                    ->pluck('ips')->first();

        $semester_ke = AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa', $id_reg)->whereRaw("RIGHT(id_semester, 1) != 3")->count();

        if($semester_ke == 1 || $semester_ke == 2 ){
            $sks_max = 20;
        }else{
            if ($ips !== null) {
                if ($ips >= 3.00) {
                    $sks_max = 24;
                } elseif ($ips >= 2.50 && $ips <= 2.99) {
                    $sks_max = 21;
                } elseif ($ips >= 2.00 && $ips <= 2.49) {
                    $sks_max = 18;
                } elseif ($ips >= 1.50 && $ips <= 1.99) {
                    $sks_max = 15;
                } elseif ($ips < 1.50) {
                    $sks_max = 12;
                } else {
                    $sks_max = "Tidak Diisi";
                }
            } else {
                $sks_max = "Tidak Diisi";
            }
        }
        return $sks_max;
    }

    public function getKrsAkt($id_reg, $semester_aktif)
    {
         //DATA AKTIVITAS 
         $id_reg = auth()->user()->fk_id;

        $riwayat_pendidikan = RiwayatPendidikan::select('riwayat_pendidikans.*')
                    ->where('id_registrasi_mahasiswa', $id_reg)
                    ->first();
                    
         $db = new MataKuliah();

         $data_akt = $db->getMKAktivitas($riwayat_pendidikan->id_prodi, $riwayat_pendidikan->id_kurikulum);
        //  dd($data_akt);
 
        if(isEmpty($data_akt))
        {
            $mk_akt=NULL;
            $data_akt_ids = NULL;

        }
        else
        {
            $mk_akt = $data_akt['data']['data'];
            $data_akt_ids = array_column($mk_akt, 'id_matkul');
        }
        // dd($mk_akt);
         // Ekstrak sub-array 'data' dari $data_akt
         
        
 
         // Ekstrak nilai 'id_matkul' dari sub-array 'data'
         
 
        // AKTIVITAS MAHASISWA YG DIAMBIL
        $krs_akt = AnggotaAktivitasMahasiswa::with(['aktivitas_mahasiswa.bimbing_mahasiswa', 'aktivitas_mahasiswa.konversi'])
        ->select(
            'aktivitas_mahasiswas.id', 
            'aktivitas_mahasiswas.nama_jenis_aktivitas', 
            'aktivitas_mahasiswas.nama_jenis_anggota',
            'aktivitas_mahasiswas.nama_semester',
            'aktivitas_mahasiswas.id_prodi',
            'aktivitas_mahasiswas.lokasi',
            'aktivitas_mahasiswas.mk_konversi',
            'anggota_aktivitas_mahasiswas.id_aktivitas', 
            'anggota_aktivitas_mahasiswas.nim', 
            'anggota_aktivitas_mahasiswas.judul', 
            'anggota_aktivitas_mahasiswas.id_registrasi_mahasiswa', 
            'bimbing_mahasiswas.nama_kategori_kegiatan',
            'bimbing_mahasiswas.approved',
            // 'anggota_aktivitas_mahasiswas.*','aktivitas_mahasiswas.*', 'bimbing_mahasiswas.*'
            )
            ->leftJoin('aktivitas_mahasiswas', 'aktivitas_mahasiswas.id_aktivitas', '=', 'anggota_aktivitas_mahasiswas.id_aktivitas')
            ->leftJoin('bimbing_mahasiswas', 'bimbing_mahasiswas.id_aktivitas', '=', 'anggota_aktivitas_mahasiswas.id_aktivitas')
            ->where('anggota_aktivitas_mahasiswas.id_registrasi_mahasiswa', $id_reg)
            ->where('aktivitas_mahasiswas.id_semester', $semester_aktif->id_semester)
            ->where('aktivitas_mahasiswas.id_prodi', $riwayat_pendidikan->id_prodi)
            ->whereIn('aktivitas_mahasiswas.id_jenis_aktivitas', ['1','2', '3', '4','6','15', '22'])
            ->whereNot('bimbing_mahasiswas.id_bimbing_mahasiswa', NUll)
            // ->orderBy('nama_kelas_kuliah', 'DESC')
            // ->limit(10)
            ->groupBy(
                'aktivitas_mahasiswas.id', 
                'aktivitas_mahasiswas.nama_jenis_aktivitas', 
                'aktivitas_mahasiswas.nama_jenis_anggota',
                'aktivitas_mahasiswas.nama_semester',
                'aktivitas_mahasiswas.id_prodi',
                'aktivitas_mahasiswas.lokasi',
                'aktivitas_mahasiswas.mk_konversi',
                'anggota_aktivitas_mahasiswas.id_aktivitas', 
                'anggota_aktivitas_mahasiswas.nim', 
                'anggota_aktivitas_mahasiswas.judul', 
                'anggota_aktivitas_mahasiswas.id_registrasi_mahasiswa', 
                'bimbing_mahasiswas.nama_kategori_kegiatan',
                'bimbing_mahasiswas.approved',
            )
            ->get();
        
            return [$krs_akt, $data_akt_ids, $mk_akt];
    }

    public function getKrsRegular($id_reg, $riwayat_pendidikan, $semester_aktif, $data_akt_ids)
    {
        $krs_regular = PesertaKelasKuliah::select('peserta_kelas_kuliahs.*','kelas_kuliahs.id_prodi', 'kelas_kuliahs.jadwal_hari', 'kelas_kuliahs.jadwal_jam_mulai', 'kelas_kuliahs.jadwal_jam_selesai', 'mata_kuliahs.sks_mata_kuliah')
                ->leftJoin('kelas_kuliahs', 'peserta_kelas_kuliahs.id_kelas_kuliah', '=', 'kelas_kuliahs.id_kelas_kuliah')
                ->leftJoin('mata_kuliahs', 'mata_kuliahs.id_matkul', '=', 'peserta_kelas_kuliahs.id_matkul')
                ->where('kelas_kuliahs.id_prodi', $riwayat_pendidikan->id_prodi)
                ->where('id_registrasi_mahasiswa', $id_reg)
                
                ->where('id_semester', $semester_aktif->id_semester)
                ->get();

        if (!empty($data_akt_ids)) {
            $krs_regular->whereNotIn('peserta_kelas_kuliahs.id_matkul', $data_akt_ids);
        }
        
        return $krs_regular;
    }

    public function getKrsMerdeka($id_reg)
    {
        $krs_merdeka = PesertaKelasKuliah::select('peserta_kelas_kuliahs.*','kelas_kuliahs.id_prodi', 'kelas_kuliahs.jadwal_hari', 'kelas_kuliahs.jadwal_jam_mulai', 'kelas_kuliahs.jadwal_jam_selesai', 'mata_kuliahs.sks_mata_kuliah')
                ->join('matkul_merdekas', 'matkul_merdekas.id_matkul', '=', 'peserta_kelas_kuliahs.id_matkul')
                ->leftJoin('mata_kuliahs', 'mata_kuliahs.id_matkul', '=', 'peserta_kelas_kuliahs.id_matkul')
                ->leftJoin('kelas_kuliahs', 'kelas_kuliahs.id_kelas_kuliah', '=', 'peserta_kelas_kuliahs.id_kelas_kuliah')
                ->where('id_registrasi_mahasiswa', $id_reg)
                ->get();

        return $krs_merdeka;

    }

    public function getMKMerdeka($prodi, $semester_aktif)
    {
        $mk_merdeka = MatkulMerdeka::leftJoin('mata_kuliahs', 'matkul_merdekas.id_matkul', '=', 'mata_kuliahs.id_matkul')
                ->leftJoin('matkul_kurikulums','matkul_kurikulums.id_matkul','mata_kuliahs.id_matkul')
                ->select('mata_kuliahs.id_matkul', 'mata_kuliahs.kode_mata_kuliah', 'mata_kuliahs.nama_mata_kuliah', 'matkul_kurikulums.semester', 'matkul_kurikulums.sks_mata_kuliah')
                ->addSelect(DB::raw("(select count(id) from kelas_kuliahs where kelas_kuliahs.id_matkul=mata_kuliahs.id_matkul and kelas_kuliahs.id_semester='".$semester_aktif['id_semester']."') AS jumlah_kelas_kuliah"))
                ->orderBy('jumlah_kelas_kuliah', 'DESC')
                ->orderBy('matkul_kurikulums.semester')
                ->whereIn('mata_kuliahs.id_prodi', $prodi->pluck('id')) // Hanya mengambil mata kuliah yang termasuk dalam program studi yang dipilih
                ->orderBy('matkul_kurikulums.sks_mata_kuliah')
                ->get();

        return $mk_merdeka;
    }

    public function getMKRegular($riwayat_pendidikan, $data_akt_ids, $semester_aktif)
    {
        $matakuliah = MataKuliah::leftJoin('matkul_kurikulums', 'matkul_kurikulums.id_matkul', '=', 'mata_kuliahs.id_matkul')
            ->leftJoin('list_kurikulums', 'list_kurikulums.id_kurikulum', '=', 'matkul_kurikulums.id_kurikulum')
            ->leftJoin('kelas_kuliahs', 'kelas_kuliahs.id_matkul', '=', 'mata_kuliahs.id_matkul')
            ->select(
                'mata_kuliahs.id_matkul',
                'mata_kuliahs.kode_mata_kuliah',
                'mata_kuliahs.nama_mata_kuliah',
                'matkul_kurikulums.semester',
                'mata_kuliahs.sks_mata_kuliah',
                'kelas_kuliahs.id_prodi as id_prodi_kelas',
                'list_kurikulums.nama_kurikulum',
                'list_kurikulums.is_active'
            )
            ->addSelect(DB::raw("(select count(id) from kelas_kuliahs where kelas_kuliahs.id_matkul = mata_kuliahs.id_matkul and kelas_kuliahs.id_semester = '{$semester_aktif['id_semester']}') as jumlah_kelas_kuliah"))
            ->where('mata_kuliahs.id_prodi', $riwayat_pendidikan->id_prodi)
            ->where('matkul_kurikulums.id_kurikulum', $riwayat_pendidikan->id_kurikulum)
            ->where('list_kurikulums.is_active', '1');

        if (!empty($data_akt_ids)) {
            $matakuliah->whereNotIn('mata_kuliahs.id_matkul', $data_akt_ids);
        }

        $matakuliah = $matakuliah->groupBy(
                'mata_kuliahs.id_matkul',
                'mata_kuliahs.kode_mata_kuliah',
                'mata_kuliahs.nama_mata_kuliah',
                'matkul_kurikulums.semester',
                'mata_kuliahs.sks_mata_kuliah',
                'kelas_kuliahs.id_prodi',
                'list_kurikulums.nama_kurikulum',
                'list_kurikulums.is_active'
            )
            ->orderBy('jumlah_kelas_kuliah', 'DESC')
            ->orderBy('matkul_kurikulums.semester')
            ->orderBy('matkul_kurikulums.sks_mata_kuliah')
            ->get();

        return $matakuliah;

    }

    public function getMKAktivitas($id_prodi, $id_kurikulum)
    {
        $data=
        [
            [
                "id_prodi"=> "f371d293-c602-4b1b-afc5-222081477091",
                "data"=>
                [
                    "id_kurikulum"=>"5f0173e8-8f43-4819-be60-41dcb73f9449",
                    "data"=>
                    [
                        [
                            "id_matkul"=>"b010c3b4-b4e3-4369-80a5-f8ddc9fc3529",
                            "kode_mata_kuliah"=>"TKM4001",
                            "nama_mata_kuliah"=>"KERJA PRAKTEK",
                            "id_jenis_mata_kuliah"=>"A",
                            "sks_mata_kuliah"=>2.00,
                            "semester"=>7,
                        ],
                        [
                            "id_matkul"=>"bff65f45-ee5d-4f6f-bf5f-eddf00fe6d9c",
                            "kode_mata_kuliah"=>"TKM4002",
                            "nama_mata_kuliah"=>"TUGAS AKHIR",
                            "id_jenis_mata_kuliah"=>"S",
                            "sks_mata_kuliah"=>5.00,
                            "semester"=>8,
                        ],
                    ],

                    // "id_kurikulum"=>"7699c236-04ae-4c63-974a-b47f29b03091",
                    // "data"=>
                    // [
                    //     [
                    //         "id_matkul"=>"4f915940-8835-4ae1-ab57-83bdc9978447",
                    //         "kode_mata_kuliah"=>"TKM490514",
                    //         "nama_mata_kuliah"=>"SKRIPSI",
                    //         "id_jenis_mata_kuliah"=>"S",
                    //         "sks_mata_kuliah"=>5.00,
                    //         "semester"=>8,
                    //     ],
                    //     [
                    //         "id_matkul"=>"b5981e2b-061b-4c6f-ac45-1cca1f180a89",
                    //         "kode_mata_kuliah"=>"TKM490314",
                    //         "nama_mata_kuliah"=>"PROPOSAL SKRIPSI",
                    //         "id_jenis_mata_kuliah"=>"S",
                    //         "sks_mata_kuliah"=>1.00,
                    //         "semester"=>8,
                    //     ],
                    // ]
                ]
            ],
            [
                "id_prodi"=> "c9091879-6fd9-4691-bea8-283186c27ad1",
                "data"=>
                [
                    "id_kurikulum"=>"6cc57ff6-9b9e-4bed-b9f9-e51999efb99d",
                    "data"=>
                    [
                        [
                            "id_matkul"=>"2b394017-4e20-409b-a8a4-ca6e3ffb9e4d",
                            "kode_mata_kuliah"=>"FTI4001",
                            "nama_mata_kuliah"=>"KERJA PRAKTIK",
                            "id_jenis_mata_kuliah"=>"A",
                            "sks_mata_kuliah"=>3.00,
                            "semester"=>7,
                        ],
                        [
                            "id_matkul"=>"1301a6e0-371b-40b6-8cd4-37119ac8a491",
                            "kode_mata_kuliah"=>"FTI4015",
                            "nama_mata_kuliah"=>"KULIAH KERJA NYATA",
                            "id_jenis_mata_kuliah"=>"A",
                            "sks_mata_kuliah"=>3.00,
                            "semester"=>7,
                        ],
                        [
                            "id_matkul"=>"a96fe827-4811-4c65-973f-079dc398d7cb",
                            "kode_mata_kuliah"=>"FTI4017",
                            "nama_mata_kuliah"=>"SKRIPSI",
                            "id_jenis_mata_kuliah"=>"A",
                            "sks_mata_kuliah"=>6.00,
                            "semester"=>8,
                        ],
                    ]
                ]        
            ]
        ];
        foreach ($data as $prodi) {
            if ($prodi['id_prodi'] == $id_prodi) {
                return $prodi;
            }
        }

        // foreach ($data as $prodi) {
        //     if ($prodi['id_prodi'] == $id_prodi) {
        //         foreach ($prodi['data'] as $kurikulum) {
        //             if ($kurikulum['id_kurikulum'] == $id_kurikulum) {
        //                 return $kurikulum;
        //             }
        //         }
        //     }
        // }

        return [];
    }



}
