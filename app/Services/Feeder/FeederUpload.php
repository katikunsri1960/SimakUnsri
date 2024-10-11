<?php

namespace App\Services\Feeder;

use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Semester;
use GuzzleHttp\Client;

class FeederUpload {
    // url Feeder Dikti
    private $url;
    // Username Feeder Dikti
    private $username;
    // Password
    private $password;
    //data
    private $act, $record, $actGet, $recordGet;


    function __construct($act, $record, $actGet, $recordGet) {

        $this->url = env('FEEDER_URL');
        $this->username = env('FEEDER_USERNAME');
        $this->password = env('FEEDER_PASSWORD');
        $this->act = $act;
        $this->record = $record;
        $this->actGet = $actGet;
        $this->recordGet = $recordGet;

    }

    public function uploadRps()
    {
        $client = new Client();
        $params = [
            "act" => "GetToken",
            "username" => $this->username,
            "password" => $this->password,
        ];

        $req = $client->post( $this->url, [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'body' => json_encode($params)
        ]);

        $response = $req->getBody();
        $result = json_decode($response,true);

        if($result['error_code'] == 0) {
            $token = $result['data']['token'];
            $params = [
                "token" => $token,
                "act"   => $this->act,
                "record" => $this->record
            ];

            $req = $client->post( $this->url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'body' => json_encode($params)
            ]);

            $response = $req->getBody();

            $result = json_decode($response,true);

            // if(isset($result['error_code']) && $result['error_code'] == 1260)
            // {
            //     $params = [
            //         "token" => $token,
            //         "act"   => 'UpdatePerkuliahanMahasiswa',
            //         "key" => [
            //             "id_registrasi_mahasiswa" => $this->record['id_registrasi_mahasiswa'],
            //             "id_semester" => $this->record['id_semester']
            //         ],
            //         "record" => [
            //             "id_status_mahasiswa" => $this->record['id_status_mahasiswa'],
            //             "ips" => $this->record['ips'],
            //             "ipk" => $this->record['ipk'],
            //             "sks_semester" => $this->record['sks_semester'],
            //             "total_sks" => $this->record['total_sks'],
            //             "biaya_kuliah_smt" => $this->record['biaya_kuliah_smt'],
            //             "id_pembiayaan" => $this->record['id_pembiayaan']
            //         ]
            //     ];

            //     $req = $client->post( $this->url, [
            //         'headers' => [
            //             'Content-Type' => 'application/json',
            //             'Accept' => 'application/json',
            //         ],
            //         'body' => json_encode($params)
            //     ]);

            //     $response = $req->getBody();

            //     $result = json_decode($response,true);

            // }

            // error_codee 1260 = data sudah ada

            return $result;
        }
    }

    public function uploadAkm()
    {
        // dd($this->url);
        $client = new Client();
        $params = [
            "act" => "GetToken",
            "username" => $this->username,
            "password" => $this->password,
        ];

        $req = $client->post( $this->url, [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'body' => json_encode($params)
        ]);

        $response = $req->getBody();
        $result = json_decode($response,true);

        if($result['error_code'] == 0) {
            $token = $result['data']['token'];
            $params = [
                "token" => $token,
                "act"   => $this->act,
                "record" => $this->record
            ];

            $req = $client->post( $this->url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'body' => json_encode($params)
            ]);

            $response = $req->getBody();

            $result = json_decode($response,true);

            if(isset($result['error_code']) && $result['error_code'] == 1260)
            {
                $params = [
                    "token" => $token,
                    "act"   => 'UpdatePerkuliahanMahasiswa',
                    "key" => [
                        "id_registrasi_mahasiswa" => $this->record['id_registrasi_mahasiswa'],
                        "id_semester" => $this->record['id_semester']
                    ],
                    "record" => [
                        "id_status_mahasiswa" => $this->record['id_status_mahasiswa'],
                        "ips" => $this->record['ips'],
                        "ipk" => $this->record['ipk'],
                        "sks_semester" => $this->record['sks_semester'],
                        "total_sks" => $this->record['total_sks'],
                        "biaya_kuliah_smt" => $this->record['biaya_kuliah_smt'],
                        "id_pembiayaan" => $this->record['id_pembiayaan']
                    ]
                ];

                $req = $client->post( $this->url, [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                    ],
                    'body' => json_encode($params)
                ]);

                $response = $req->getBody();

                $result = json_decode($response,true);

            }

            // error_codee 1260 = data sudah ada

            return $result;
            // if ((isset($result['error_code']) && $result['error_code'] == 0) ) {

            //     $params = [
            //         "token" => $token,
            //         "act"   => $this->actGet,
            //         "filter" => $this->recordGet
            //     ];

            //     $req = $client->post( $this->url, [
            //         'headers' => [
            //             'Content-Type' => 'application/json',
            //             'Accept' => 'application/json',
            //         ],
            //         'body' => json_encode($params)
            //     ]);

            //     $response = $req->getBody();

            //     $result = json_decode($response,true);

            //     return $result;
            // }

        }

    }

    public function uploadAktivitas()
    {
        $client = new Client();
        $params = [
            "act" => "GetToken",
            "username" => $this->username,
            "password" => $this->password,
        ];

        $req = $client->post( $this->url, [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'body' => json_encode($params)
        ]);

        $response = $req->getBody();
        $result = json_decode($response,true);

        if($result['error_code'] == 0) {

            $token = $result['data']['token'];
            $paramsGet = [
                "token" => $token,
                "act"   => $this->actGet,
                "filter" => $this->recordGet
            ];

            $req = $client->post( $this->url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'body' => json_encode($paramsGet)
            ]);

            $response = $req->getBody();

            $result = json_decode($response,true);

            if ($result['error_code'] == 0 && count($result['data']) > 0) {

                $updateRecord = $this->record;

                unset($updateRecord['id_aktivitas']);

                $paramsUpdate = [
                    "token" => $token,
                    "act"   => "UpdateAktivitasMahasiswa",
                    "key" => [
                        "id_aktivitas" => $this->record['id_aktivitas']
                    ],
                    "record" => $updateRecord
                ];

                $req = $client->post( $this->url, [
                        'headers' => [
                            'Content-Type' => 'application/json',
                            'Accept' => 'application/json',
                        ],
                        'body' => json_encode($paramsUpdate)
                ]);

                $response = $req->getBody();

                $result = json_decode($response,true);

            } else {

                    unset($this->record['id_aktivitas']);

                    $params = [
                        "token" => $token,
                        "act"   => $this->act,
                        "record" => $this->record
                    ];

                    $req = $client->post( $this->url, [
                        'headers' => [
                            'Content-Type' => 'application/json',
                            'Accept' => 'application/json',
                        ],
                        'body' => json_encode($params)
                    ]);

                    $response = $req->getBody();

                    $result = json_decode($response,true);
            }

            return $result;
        }
    }

    public function uploadKelas()
    {
        $client = new Client();
        $params = [
            "act" => "GetToken",
            "username" => $this->username,
            "password" => $this->password,
        ];

        $req = $client->post( $this->url, [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'body' => json_encode($params)
        ]);

        $response = $req->getBody();
        $result = json_decode($response,true);

        if($result['error_code'] == 0) {
            $token = $result['data']['token'];
            $params = [
                "token" => $token,
                "act"   => $this->act,
                "record" => $this->record
            ];

            $req = $client->post( $this->url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'body' => json_encode($params)
            ]);

            $response = $req->getBody();

            $result = json_decode($response,true);

            // if(isset($result['error_code']) && $result['error_code'] == 1260)
            // {
            //     $params = [
            //         "token" => $token,
            //         "act"   => 'UpdatePerkuliahanMahasiswa',
            //         "key" => [
            //             "id_registrasi_mahasiswa" => $this->record['id_registrasi_mahasiswa'],
            //             "id_semester" => $this->record['id_semester']
            //         ],
            //         "record" => [
            //             "id_status_mahasiswa" => $this->record['id_status_mahasiswa'],
            //             "ips" => $this->record['ips'],
            //             "ipk" => $this->record['ipk'],
            //             "sks_semester" => $this->record['sks_semester'],
            //             "total_sks" => $this->record['total_sks'],
            //             "biaya_kuliah_smt" => $this->record['biaya_kuliah_smt'],
            //             "id_pembiayaan" => $this->record['id_pembiayaan']
            //         ]
            //     ];

            //     $req = $client->post( $this->url, [
            //         'headers' => [
            //             'Content-Type' => 'application/json',
            //             'Accept' => 'application/json',
            //         ],
            //         'body' => json_encode($params)
            //     ]);

            //     $response = $req->getBody();

            //     $result = json_decode($response,true);

            // }

            // error_codee 1260 = data sudah ada

            return $result;
        }
    }

    public function uploadKomponen()
    {
        $client = new Client();
        $params = [
            "act" => "GetToken",
            "username" => $this->username,
            "password" => $this->password,
        ];

        $req = $client->post( $this->url, [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'body' => json_encode($params)
        ]);

        $response = $req->getBody();
        $result = json_decode($response,true);

        if($result['error_code'] == 0) {
            $token = $result['data']['token'];

            $id_evaluasi = $this->record['id_komponen_evaluasi'];

            unset($this->record['id_komponen_evaluasi']);

            $params = [
                "token" => $token,
                "act"   => $this->act,
                "record" => $this->record
            ];

            $req = $client->post( $this->url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'body' => json_encode($params)
            ]);

            $response = $req->getBody();

            $result = json_decode($response,true);

            if(isset($result['error_code']) && $result['error_code'] != 0)
            {
                $params = [
                    "token" => $token,
                    "act"   => 'UpdateKomponenEvaluasiKelas',
                    "key" => [
                        "id_komponen_evaluasi" => $id_evaluasi,
                    ],
                    "record" => [
                        'id_kelas_kuliah' => $this->record['id_kelas_kuliah'],
                        'id_jenis_evaluasi' => $this->record['id_jenis_evaluasi'],
                        'nama' => $this->record['nama'],
                        'nama_inggris' =>$this->record['nama_inggris'],
                        'nomor_urut' => $this->record['nomor_urut'],
                        'bobot_evaluasi' => $this->record['bobot_evaluasi'],
                    ]
                ];

                $req = $client->post( $this->url, [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                    ],
                    'body' => json_encode($params)
                ]);

                $response = $req->getBody();

                $result = json_decode($response,true);

            }

            // error_codee 1260 = data sudah ada

            return $result;
        }
    }

    public function uploadGeneral()
    {
        $client = new Client();
        $params = [
            "act" => "GetToken",
            "username" => $this->username,
            "password" => $this->password,
        ];

        $req = $client->post( $this->url, [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'body' => json_encode($params)
        ]);

        $response = $req->getBody();
        $result = json_decode($response,true);

        if($result['error_code'] == 0) {
            $token = $result['data']['token'];
            $params = [
                "token" => $token,
                "act"   => $this->act,
                "record" => $this->record
            ];

            $req = $client->post( $this->url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'body' => json_encode($params)
            ]);

            $response = $req->getBody();

            $result = json_decode($response,true);

            // if(isset($result['error_code']) && $result['error_code'] == 1260)
            // {
            //     $params = [
            //         "token" => $token,
            //         "act"   => 'UpdatePerkuliahanMahasiswa',
            //         "key" => [
            //             "id_registrasi_mahasiswa" => $this->record['id_registrasi_mahasiswa'],
            //             "id_semester" => $this->record['id_semester']
            //         ],
            //         "record" => [
            //             "id_status_mahasiswa" => $this->record['id_status_mahasiswa'],
            //             "ips" => $this->record['ips'],
            //             "ipk" => $this->record['ipk'],
            //             "sks_semester" => $this->record['sks_semester'],
            //             "total_sks" => $this->record['total_sks'],
            //             "biaya_kuliah_smt" => $this->record['biaya_kuliah_smt'],
            //             "id_pembiayaan" => $this->record['id_pembiayaan']
            //         ]
            //     ];

            //     $req = $client->post( $this->url, [
            //         'headers' => [
            //             'Content-Type' => 'application/json',
            //             'Accept' => 'application/json',
            //         ],
            //         'body' => json_encode($params)
            //     ]);

            //     $response = $req->getBody();

            //     $result = json_decode($response,true);

            // }

            // error_codee 1260 = data sudah ada

            return $result;
        }
    }

    public function uploadNilaiKomponen()
    {
        $client = new Client();
        $params = [
            "act" => "GetToken",
            "username" => $this->username,
            "password" => $this->password,
        ];

        $req = $client->post( $this->url, [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'body' => json_encode($params)
        ]);

        $response = $req->getBody();
        $result = json_decode($response,true);

        if($result['error_code'] == 0) {
            $token = $result['data']['token'];
            $params = [
                "token" => $token,
                "act"   => $this->act,
                "key" => [
                    "id_komponen_evaluasi" => $this->record['id_komponen_evaluasi'],
                    "id_registrasi_mahasiswa" => $this->record['id_registrasi_mahasiswa'],
                ],
                "record" => [
                    "nilai_komponen_evaluasi" => $this->record['nilai_komponen_evaluasi'],
                ],
            ];

            $req = $client->post( $this->url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'body' => json_encode($params)
            ]);

            $response = $req->getBody();

            $result = json_decode($response,true);

            // if(isset($result['error_code']) && $result['error_code'] == 1260)
            // {
            //     $params = [
            //         "token" => $token,
            //         "act"   => 'UpdatePerkuliahanMahasiswa',
            //         "key" => [
            //             "id_registrasi_mahasiswa" => $this->record['id_registrasi_mahasiswa'],
            //             "id_semester" => $this->record['id_semester']
            //         ],
            //         "record" => [
            //             "id_status_mahasiswa" => $this->record['id_status_mahasiswa'],
            //             "ips" => $this->record['ips'],
            //             "ipk" => $this->record['ipk'],
            //             "sks_semester" => $this->record['sks_semester'],
            //             "total_sks" => $this->record['total_sks'],
            //             "biaya_kuliah_smt" => $this->record['biaya_kuliah_smt'],
            //             "id_pembiayaan" => $this->record['id_pembiayaan']
            //         ]
            //     ];

            //     $req = $client->post( $this->url, [
            //         'headers' => [
            //             'Content-Type' => 'application/json',
            //             'Accept' => 'application/json',
            //         ],
            //         'body' => json_encode($params)
            //     ]);

            //     $response = $req->getBody();

            //     $result = json_decode($response,true);

            // }

            // error_codee 1260 = data sudah ada

            return $result;
        }
    }

    public function uploadNilaiKonversi()
    {
        $client = new Client();
        $params = [
            "act" => "GetToken",
            "username" => $this->username,
            "password" => $this->password,
        ];

        $req = $client->post( $this->url, [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'body' => json_encode($params)
        ]);

        $response = $req->getBody();
        $result = json_decode($response,true);

        if($result['error_code'] == 0) {
            $token = $result['data']['token'];
            $id_reg = $this->record['id_registrasi_mahasiswa'];
            $id_matkul = $this->record['id_matkul'];

            unset($this->record['id_registrasi_mahasiswa']);

            $params = [
                "token" => $token,
                "act"   => $this->act,
                "record" => $this->record
            ];

            $req = $client->post( $this->url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'body' => json_encode($params)
            ]);

            $response = $req->getBody();

            $result = json_decode($response,true);

            if (isset($result['error_code']) && $result['error_code'] == 0) {

                $id_konversi_aktivitas = $result['data']['id_konversi_aktivitas'];
                $smtDiambil = $this->smtDiambil($id_reg, $this->record['id_semester']);
                $this->insertTranskrip($this->recordGet, $token, $id_reg, $id_matkul, $id_konversi_aktivitas, $smtDiambil);

            }

            return $result;
        }
    }

    private function smtDiambil($id_reg, $id_semester)
    {
        $periode_masuk = RiwayatPendidikan::where('id_registrasi_mahasiswa', $id_reg)
                                        ->select('id_periode_masuk')
                                        ->first()->id_periode_masuk;

        $semester_ke = Semester::orderBy('id_semester', 'ASC')
                                        ->whereBetween('id_semester', [$periode_masuk, $id_semester])
                                        ->whereRaw('RIGHT(id_semester, 1) != ?', [3])
                                        ->count();

        return $semester_ke;


    }

    private function insertTranskrip($recordGet, $token, $id_reg, $id_matkul, $id_konversi_aktivitas, $smtDiambil)
    {
        $client = new Client();

        $params = [
            "token" => $token,
            "act"   => "GetTranskripMahasiswa",
            "filter" => $recordGet
        ];

        $req = $client->post( $this->url, [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'body' => json_encode($params)
        ]);

        $response = $req->getBody();

        $result = json_decode($response,true);

        if (isset($result['error_code']) && $result['error_code'] == 0 && count($result['data']) > 0) {

            $params = [
                "token" => $token,
                "act"   => "DeleteTranskripMahasiswa",
                "key" => [
                    "id_registrasi_mahasiswa" => $id_reg,
                    "id_matkul" => $id_matkul
                ]
            ];

            $req = $client->post( $this->url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'body' => json_encode($params)
            ]);

            $response = $req->getBody();

            $result = json_decode($response,true);
        }

        $params = [
            'token' => $token,
            'act' => 'InsertTranskripMahasiswa',
            'record' => [
                'id_registrasi_mahasiswa' => $id_reg,
                'id_matkul' => $id_matkul,
                'id_konversi_aktivitas' => $id_konversi_aktivitas,
                'smt_diambil' => strval($smtDiambil),
            ]
        ];

        $req = $client->post( $this->url, [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'body' => json_encode($params)
        ]);

        $response = $req->getBody();

        $result = json_decode($response,true);

        return true;
    }

    public function uploadNilaiKelas()
    {
        $client = new Client();
        $params = [
            "act" => "GetToken",
            "username" => $this->username,
            "password" => $this->password,
        ];

        $req = $client->post( $this->url, [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'body' => json_encode($params)
        ]);

        $response = $req->getBody();
        $result = json_decode($response,true);

        if($result['error_code'] == 0) {
            $token = $result['data']['token'];
            $params = [
                "token" => $token,
                "act"   => $this->act,
                "key" => [
                    "id_kelas_kuliah" => $this->record['id_kelas_kuliah'],
                    "id_registrasi_mahasiswa" => $this->record['id_registrasi_mahasiswa'],
                ],
                "record" => [
                    "nilai_angka" => $this->record['nilai_angka'],
                    "nilai_huruf" => $this->record['nilai_huruf'],
                    "nilai_indeks" => $this->record['nilai_indeks'],
                ],
            ];

            $req = $client->post( $this->url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'body' => json_encode($params)
            ]);

            $response = $req->getBody();

            $result = json_decode($response,true);

            // if(isset($result['error_code']) && $result['error_code'] == 1260)
            // {
            //     $params = [
            //         "token" => $token,
            //         "act"   => 'UpdatePerkuliahanMahasiswa',
            //         "key" => [
            //             "id_registrasi_mahasiswa" => $this->record['id_registrasi_mahasiswa'],
            //             "id_semester" => $this->record['id_semester']
            //         ],
            //         "record" => [
            //             "id_status_mahasiswa" => $this->record['id_status_mahasiswa'],
            //             "ips" => $this->record['ips'],
            //             "ipk" => $this->record['ipk'],
            //             "sks_semester" => $this->record['sks_semester'],
            //             "total_sks" => $this->record['total_sks'],
            //             "biaya_kuliah_smt" => $this->record['biaya_kuliah_smt'],
            //             "id_pembiayaan" => $this->record['id_pembiayaan']
            //         ]
            //     ];

            //     $req = $client->post( $this->url, [
            //         'headers' => [
            //             'Content-Type' => 'application/json',
            //             'Accept' => 'application/json',
            //         ],
            //         'body' => json_encode($params)
            //     ]);

            //     $response = $req->getBody();

            //     $result = json_decode($response,true);

            // }

            // error_codee 1260 = data sudah ada

            return $result;
        }
    }
}
