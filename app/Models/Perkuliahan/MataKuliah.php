<?php

namespace App\Models\Perkuliahan;

use App\Models\ProgramStudi;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\SemesterAktif;
use Doctrine\DBAL\Query\Limit;
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

    public function matkul_kurikulum()
    {
        return $this->belongsTo(MatkulKurikulum::class, 'id_matkul', 'id_matkul');
    }

    public function matkul_merdeka()
    {
        return $this->belongsTo(MatkulMerdeka::class, 'id_matkul', 'id_matkul');
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

        return $result;
    }

    public function getSksMax($id_reg, $id_semester)
    {
        $ips = AktivitasKuliahMahasiswa::select('ips')
                    ->where('id_registrasi_mahasiswa', $id_reg)
                    ->where('id_semester', $id_semester)
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

    public function getKrsRegular($id_reg, $riwayat_pendidikan, $id_semester, $data_akt_ids)
    {
        $krs_regular = PesertaKelasKuliah::select('peserta_kelas_kuliahs.*','kelas_kuliahs.id_prodi', 'kelas_kuliahs.jadwal_hari', 'kelas_kuliahs.jadwal_jam_mulai', 'kelas_kuliahs.jadwal_jam_selesai', 'mata_kuliahs.sks_mata_kuliah')
                ->leftJoin('kelas_kuliahs', 'peserta_kelas_kuliahs.id_kelas_kuliah', '=', 'kelas_kuliahs.id_kelas_kuliah')
                ->leftJoin('mata_kuliahs', 'mata_kuliahs.id_matkul', '=', 'peserta_kelas_kuliahs.id_matkul')
                ->where('kelas_kuliahs.id_prodi', $riwayat_pendidikan->id_prodi)
                ->where('id_registrasi_mahasiswa', $id_reg)

                ->where('id_semester', $id_semester)
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


    public function getMKMerdeka($semester_aktif, $id_prodi)
    {
        $mk_merdeka = $this->with(['kelas_kuliah', 'rencana_pembelajaran', 'matkul_merdeka','matkul_kurikulum'])
                        ->whereHas('matkul_merdeka', function($query) use($id_prodi) {
                            $query->where('id_prodi', $id_prodi);
                        })
                        ->withCount(['kelas_kuliah as jumlah_kelas' => function ($q) use($semester_aktif){
                            $q->where('id_semester', $semester_aktif);
                        },
                        'rencana_pembelajaran as jumlah_rps' => function ($q) {
                                $q->where('approved', 1);
                            }
                        ])
                        ->get();

        return $mk_merdeka;
    }

    public function getMKRegular()
    {
        $id_semester = SemesterAktif::first();
        $riwayat = RiwayatPendidikan::where('id_registrasi_mahasiswa', auth()->user()->fk_id)->first();
        $prodi = $riwayat->id_prodi;
        $kurikulum = $riwayat->id_kurikulum;

        $data_akt = $this->getMKAktivitas($prodi, $kurikulum);


        if($data_akt == NULL)
        {
            $mk_akt=NULL;
            $data_akt_ids = NULL;

        }
        else
        {
            $mk_akt = $data_akt;
            $data_akt_ids = array_column($mk_akt, 'id_matkul');
        }

        $matakuliah = $this->with(['kurikulum','matkul_kurikulum', 'kelas_kuliah','kelas_kuliah.dosen_pengajar', 'rencana_pembelajaran'])
                        ->whereHas('kurikulum' , function($query) use($kurikulum, $prodi) {
                            $query->where('list_kurikulums.id_kurikulum', $kurikulum)
                                ->where('list_kurikulums.id_prodi', $prodi);
                        })
                        ->whereHas('kelas_kuliah' , function($query) use($id_semester, $prodi) {
                            $query->where('kelas_kuliahs.id_semester', $id_semester->id_semester)
                                    ->where('kelas_kuliahs.id_prodi', $prodi);
                        })

                        ->withCount(['kelas_kuliah as jumlah_kelas' => function ($q) use($id_semester, $prodi){
                            $q->where('id_semester', $id_semester->id_semester)
                                ->where('id_prodi', $prodi);
                        },
                        'rencana_pembelajaran as jumlah_rps' => function ($q) {
                            $q->where('approved', 1);
                        }]);

        // dd($matakuliah->get());
        if ($data_akt_ids != NULL) {

            $matakuliah= $matakuliah->whereNotIn('id_matkul', $data_akt_ids);
        }

        $matakuliah =  $matakuliah->orderBy('jumlah_kelas', 'DESC')
                    ->orderBy('jumlah_rps', 'ASC')
                    ->get();
                    // dd($matakuliah);

        return $matakuliah;
    }

    public function getMKAktivitas($id_prodi, $id_kurikulum)
    {
        $data=
        [
            [
                "id_prodi"=> "f371d293-c602-4b1b-afc5-222081477091",
                "data"=>[
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
                    ],
                    [
                        "id_kurikulum"=>"7699c236-04ae-4c63-974a-b47f29b03091",
                        "data"=>
                        [
                            [
                                "id_matkul"=>"4f915940-8835-4ae1-ab57-83bdc9978447",
                                "kode_mata_kuliah"=>"TKM490514",
                                "nama_mata_kuliah"=>"SKRIPSI",
                                "id_jenis_mata_kuliah"=>"S",
                                "sks_mata_kuliah"=>5.00,
                                "semester"=>8,
                            ],
                            [
                                "id_matkul"=>"b5981e2b-061b-4c6f-ac45-1cca1f180a89",
                                "kode_mata_kuliah"=>"TKM490314",
                                "nama_mata_kuliah"=>"PROPOSAL SKRIPSI",
                                "id_jenis_mata_kuliah"=>"S",
                                "sks_mata_kuliah"=>1.00,
                                "semester"=>8,
                            ],
                        ]
                    ]
                ]
            ],
            [
                "id_prodi"=> "c9091879-6fd9-4691-bea8-283186c27ad1",
                "data"=>
                [
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
                    ],
            ],


            [
                "id_prodi"=> "5c1370e1-dfd1-4137-af50-a24025696602",
                "data"=>
                [
                    [   
                        "id_kurikulum"=>"f577582a-9ef0-4a04-aed8-a84a0dd8714b",
                        "data"=>
                            [
                                [
                                    "id_matkul"=>"f2595b3a-8a35-4a1c-bf01-9ade1594f3a4",
                                    "kode_mata_kuliah"=>"FIk406220",
                                    "nama_mata_kuliah"=>"TESIS",
                                    "id_jenis_mata_kuliah"=>"S",
                                    "sks_mata_kuliah"=>6.00,
                                    "semester"=>3,
                                ]
                                // ,
                                // [
                                //     "id_matkul"=>"1301a6e0-371b-40b6-8cd4-37119ac8a491",
                                //     "kode_mata_kuliah"=>"FTI4015",
                                //     "nama_mata_kuliah"=>"KULIAH KERJA NYATA",
                                //     "id_jenis_mata_kuliah"=>"A",
                                //     "sks_mata_kuliah"=>3.00,
                                //     "semester"=>7,
                                // ],
                                // [
                                //     "id_matkul"=>"a96fe827-4811-4c65-973f-079dc398d7cb",
                                //     "kode_mata_kuliah"=>"FTI4017",
                                //     "nama_mata_kuliah"=>"SKRIPSI",
                                //     "id_jenis_mata_kuliah"=>"A",
                                //     "sks_mata_kuliah"=>6.00,
                                //     "semester"=>8,
                                // ],
                            ]
                        ]
                    ],
            ]
        ];

        $dataAkt = [];
        // dd($id_prodi);
        foreach ($data as $d) {
            
            if ($d['id_prodi'] === $id_prodi) {
                foreach ($d['data'] as $kurikulumKey => $kurikulumValue) {
                    
                    if (is_array($kurikulumValue) && array_key_exists('id_kurikulum', $kurikulumValue) && $kurikulumValue['id_kurikulum'] === $id_kurikulum) {
                        // dd($kurikulumValue['data']);
                        return $kurikulumValue['data'];
                       
                    }
                }
            }
        }
        // dd($data);
        return null;
    }

}
