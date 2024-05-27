<?php

namespace App\Models\Perkuliahan;

use App\Models\ProgramStudi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function getMKAktivitas($id_prodi)
    {
        $data=
        [
            [
                "id_prodi"=> "f371d293-c602-4b1b-afc5-222081477091",
                "data"=>
                [
                    // "id_kurikulum"=>"5f0173e8-8f43-4819-be60-41dcb73f9449",
                    // "data"=>
                    // [
                    //     [
                    //         "id_matkul"=>"b010c3b4-b4e3-4369-80a5-f8ddc9fc3529",
                    //         "kode_mata_kuliah"=>"TKM4001",
                    //         "nama_mata_kuliah"=>"KERJA PRAKTEK",
                    //         "id_jenis_mata_kuliah"=>"A",
                    //         "sks_mata_kuliah"=>2.00,
                    //         "semester"=>7,
                    //     ],
                    //     [
                    //         "id_matkul"=>"bff65f45-ee5d-4f6f-bf5f-eddf00fe6d9c",
                    //         "kode_mata_kuliah"=>"TKM4002",
                    //         "nama_mata_kuliah"=>"TUGAS AKHIR",
                    //         "id_jenis_mata_kuliah"=>"S",
                    //         "sks_mata_kuliah"=>5.00,
                    //         "semester"=>8,
                    //     ],
                    // ]

                    "id_kurikulum"=>"7699c236-04ae-4c63-974a-b47f29b03091",
                    "data"=>
                    [
                        [
                            "id_matkul"=>"4f915940-8835-4ae1-ab57-83bdc9978447",
                            "kode_mata_kuliah"=>"TKM490514",
                            "nama_mata_kuliah"=>"TKM490514",
                            "id_jenis_mata_kuliah"=>"S",
                            "sks_mata_kuliah"=>5.00,
                            "semester"=>8,
                        ],
                        // [
                        //     "id_matkul"=>"bff65f45-ee5d-4f6f-bf5f-eddf00fe6d9c",
                        //     "kode_mata_kuliah"=>"TKM4002",
                        //     "nama_mata_kuliah"=>"TUGAS AKHIR",
                        //     "id_jenis_mata_kuliah"=>"S",
                        //     "sks_mata_kuliah"=>5.00,
                        //     "semester"=>8,
                        // ],
                    ]
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

        return [];
    }
}
