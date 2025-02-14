<?php

namespace App\Models\Perkuliahan;

use App\Models\Semester;
use App\Models\ProgramStudi;
use App\Models\SemesterAktif;
use Illuminate\Database\Eloquent\Model;

use App\Models\Mahasiswa\RiwayatPendidikan;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function matkul_konversi()
    {
        return $this->belongsTo(Konversi::class, 'id_matkul', 'id_matkul');
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

    public function getSksMax($id_reg, $id_semester, $id_periode_masuk)
    {
        $riwayat_pendidikan = RiwayatPendidikan::with('prodi')
                            ->where('id_registrasi_mahasiswa', $id_reg)
                            ->first();

        $prodi_fk = [
                    '98223413-b27d-4afe-a2b8-d0d80173506e',
                    'be779246-fe70-4e66-8fa2-8929d97779a2',
                    'efd6f97f-d7fc-42c1-bea0-2e5837e569d6',
                    '7c569912-fa48-4b93-8c16-1fc78969c337',
                    'a8d4f70f-406c-43f6-95ee-15f8ad836db3',
                    // 'c4bbd3bb-3b4b-4aa3-bc50-136842747c67',//S2 Biomedik
                    // '6343967c-d7e3-447c-86a4-37c5c166ad7a',//S3 Sains Biomedis
                    '947760c7-8b9b-40d2-af81-cdd141fddadb',
                    '90c23123-fb1e-48ee-9bdc-f923e799cd2a',
                    'a77fda16-ec5a-4d73-b076-51bac9b88ae4',
                    '132e62cc-dfdc-437d-9df3-e5317f80a6ff',
                    '67c6cb06-f882-48c2-8a8f-33ab9457d1a6',
                    '95290672-5f13-4776-9c0e-9c84ff0611ed',
                    'e2f2ac47-8844-456b-b525-482db9da0abf',
                    'bb06fc41-9e48-443e-aa02-df83da6bb467',
                    'b3dce9a8-25b8-4f27-96cc-2abe5e0d9fa9',
                    '9965f1cf-563f-4671-9dca-4874e8c5d075',
                    'fd61ecb4-d6b0-4135-b7e2-7e23665c3e0d',
                    '40693f4c-5177-4bd3-b3df-7321320583a6',
        ];

        $prodi_profesi=ProgramStudi::where('id_jenjang_pendidikan', '31')->where('status', 'A')->get()->pluck('id_prodi');

        $prodi_profesi = $prodi_profesi->toArray();
        // $mhs_fk= RiwayatPendidikan::where('id_registrasi_mahasiswa', $id_reg)
        //             ->whereIn('id_prodi', $prodi_fk)
        //             ->first();

        // dd($prodi_profesi);

        $akm = Semester::orderBy('id_semester', 'ASC')
                    ->whereBetween('id_semester', [$id_periode_masuk, $id_semester])
                    ->whereRaw('RIGHT(id_semester, 1) != ?', [3])
                    ->pluck('id_semester');

        // Dapatkan indeks dari semester terakhir dalam koleksi
        $index_semester_terakhir = $akm->search($akm->last());

        // Pastikan bahwa indeks tidak berada di posisi pertama
        if ($index_semester_terakhir > 0) {
            // Mundur satu semester dari yang terakhir
            $akm_sebelum = $akm[$index_semester_terakhir - 1];
        } else {
            // Jika tidak ada semester sebelumnya (semester pertama), bisa didefinisikan logika lain
            $akm_sebelum = null;
        }

        $akm_cuti = AktivitasKuliahMahasiswa::
                    where('id_registrasi_mahasiswa', $id_reg)
                    ->where('id_semester', $akm_sebelum)
                    ->where('id_status_mahasiswa', 'C')
                    ->orderBy('id_semester', 'DESC')
                    // ->pluck('ips')
                    ->count();


        if($akm_cuti > 0){
            $akm_sebelum = $akm[$index_semester_terakhir - 2];
        }


        $jenjang_pendidikan = $riwayat_pendidikan->prodi;

        // dd($riwayat_pendidikan);

        $ips = AktivitasKuliahMahasiswa::select('ips')
                    ->where('id_registrasi_mahasiswa', $id_reg)
                    ->where('id_semester', $akm_sebelum)
                    ->orderBy('id_semester', 'DESC')
                    // ->pluck('ips')
                    ->first();

                // dd($ips);

        // $semester_ke = AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa', $id_reg)->whereRaw("RIGHT(id_semester, 1) != 3")->count();
        $semester_ke = Semester::orderBy('id_semester', 'ASC')
                ->whereBetween('id_semester', [$id_periode_masuk, $id_semester])
                ->whereRaw('RIGHT(id_semester, 1) != ?', [3])
                ->count();

        // $semester_ke==3;

        // Pastikan untuk mengambil nilai ips
        $ips_value = $ips ? $ips->ips : null;

        // $non_gelar = RiwayatPendidikan::where('id_registrasi_mahasiswa', $id_reg)
        //                 ->where('id_jenis_daftar', '14')
        //                 ->count();
        $non_gelar = $riwayat_pendidikan->id_jenis_daftar == '14' ? 1 : 0;

        //  dd($non_gelar);


        if (isset($riwayat_pendidikan->id_prodi) &&
            (in_array($riwayat_pendidikan->id_prodi, $prodi_fk, true) ||
            in_array($riwayat_pendidikan->id_prodi, $prodi_profesi, true))) {
            $sks_max = 24;
        } elseif ($riwayat_pendidikan->sks_maks_pmm && $riwayat_pendidikan->id_jenis_daftar === '14'){
            $sks_max = $riwayat_pendidikan->sks_maks_pmm;
        } elseif ($jenjang_pendidikan->nama_jenjang_pendidikan == 'S2' ||
            $jenjang_pendidikan->nama_jenjang_pendidikan == 'S3'
        ) {
            $sks_max = 18;
        }elseif ($semester_ke == 1 || $semester_ke == 2 || $non_gelar > 0) {
            // dd($ips_value);
            $sks_max = 0;
            if ($ips_value !== null) {
                if ($ips_value > 2.49) {
                $sks_max = 20;
                }elseif ($ips_value >= 2.00 && $ips_value <= 2.49) {
                $sks_max = 18;
                } elseif ($ips_value >= 1.50 && $ips_value <= 1.99) {
                $sks_max = 15;
                } else {
                $sks_max = 12;
                }
            }
        } else {
            if ($ips_value !== null) {
                if ($ips_value >= 3.00) {
                    $sks_max = 24;
                } elseif ($ips_value >= 2.50 && $ips_value <= 2.99) {
                    $sks_max = 21;
                } elseif ($ips_value >= 2.00 && $ips_value <= 2.49) {
                    $sks_max = 18;
                } elseif ($ips_value >= 1.50 && $ips_value <= 1.99) {
                    $sks_max = 15;
                } elseif ($ips_value < 1.50 ) {
                    $sks_max = 12;
                } else {
                    $sks_max = 0;
                }
            } else {
                $sks_max = 0;
            }
        }
    // dd($sks_max);
        return $sks_max;

    }



    public function getKrsRegular($id_reg, $riwayat_pendidikan, $id_semester, $data_akt_ids)
    {
        $krs_regular = PesertaKelasKuliah::select('peserta_kelas_kuliahs.*','kelas_kuliahs.id_prodi', 'kelas_kuliahs.jadwal_hari', 'kelas_kuliahs.jadwal_jam_mulai', 'kelas_kuliahs.jadwal_jam_selesai', 'mata_kuliahs.sks_mata_kuliah', 'tanggal_approve')
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

    public function getKrsMerdeka($id_reg, $id_semester,  $prodi_mhs)
    {
        $krs_merdeka = PesertaKelasKuliah::select('peserta_kelas_kuliahs.*','kelas_kuliahs.id_prodi', 'kelas_kuliahs.jadwal_hari', 'kelas_kuliahs.jadwal_jam_mulai', 'kelas_kuliahs.jadwal_jam_selesai', 'mata_kuliahs.sks_mata_kuliah', 'tanggal_approve')
                ->join('matkul_merdekas', 'matkul_merdekas.id_matkul', '=', 'peserta_kelas_kuliahs.id_matkul')
                ->leftJoin('mata_kuliahs', 'mata_kuliahs.id_matkul', '=', 'peserta_kelas_kuliahs.id_matkul')
                ->leftJoin('kelas_kuliahs', 'kelas_kuliahs.id_kelas_kuliah', '=', 'peserta_kelas_kuliahs.id_kelas_kuliah')
                ->where('id_registrasi_mahasiswa', $id_reg)
                ->whereNotIn('kelas_kuliahs.id_prodi', [$prodi_mhs])
                ->where('id_semester', $id_semester)
                ->get();
                // dd($krs_merdeka);

        return $krs_merdeka;
    }

    public function getMKMerdeka($semester_aktif, $id_prodi)
    {
        // $id_matkul='eb91d8d7-22f5-498b-8f16-088e0e79c8e0';

        $mk_merdeka = $this->with(['rencana_pembelajaran', 'matkul_merdeka','matkul_kurikulum',
                        'kelas_kuliah' => function($query) use ($semester_aktif, $id_prodi) {
                            $query->where('id_semester', $semester_aktif)
                            ->where('id_prodi', $id_prodi)
                                    ;
                        }])
                        ->whereHas('matkul_merdeka', function($query) use($id_prodi) {
                            $query->where('id_prodi', $id_prodi);
                        })
                        ->withCount(['kelas_kuliah as jumlah_kelas' => function ($q) use($semester_aktif, $id_prodi){
                            $q->where('id_semester', $semester_aktif)
                            ->where('id_prodi', $id_prodi);
                            ;
                        },
                        'rencana_pembelajaran as jumlah_rps' => function ($q) {
                                $q->where('approved', 1);
                            }
                        ])
                        // ->where('id_matkul', $id_matkul)
                        ->get();

                        // dd($mk_merdeka);
        return $mk_merdeka;
    }

    public function getMKRegular()
    {
        $id_semester = SemesterAktif::first();
        $riwayat = RiwayatPendidikan::where('id_registrasi_mahasiswa', auth()->user()->fk_id)->first();
        $prodi = $riwayat->id_prodi;
        $kurikulum = $riwayat->id_kurikulum;

        $data_akt = Konversi::
                    where('id_prodi', $prodi)
                    ->where('id_kurikulum', $kurikulum)
                    ->get();
                    // dd($data_akt);

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

        $matakuliah = $this->with(['kurikulum','matkul_kurikulum',
                        'kelas_kuliah' => function($query) use ($id_semester, $prodi) {
                            $query->where('id_semester', $id_semester->id_semester)
                                    ->where('id_prodi', $prodi);
                        },'kelas_kuliah.dosen_pengajar','kelas_kuliah.ruang_perkuliahan', 'rencana_pembelajaran'])
                        ->whereHas('kurikulum' , function($query) use($kurikulum) {
                            $query->where('list_kurikulums.id_kurikulum', $kurikulum);
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

        // dd($matakuliah);
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

        $data_akt = Konversi::
                    where('id_prodi', $id_prodi)
                    ->where('id_kurikulum', $id_kurikulum)
                    ->pluck('id_matkul');

        // $dataAkt = Konversi::
        //             // with(['matkul_kurikulum'])
        //             where('id_prodi', $id_prodi)
        //             ->where('id_kurikulum', $id_kurikulum)
        //             ->pluck('id_matkul');
                    // dd($data_akt);

        return $data_akt;
    }



    public function getMKAktivitas_1($id_prodi, $id_kurikulum)
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
