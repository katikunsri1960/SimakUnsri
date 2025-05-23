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

    public function uploadPeriodePerkuliahan()
    {
        $client = new Client();
        $paramsToken = [
            "act" => "GetToken",
            "username" => $this->username,
            "password" => $this->password,
        ];

        // Mendapatkan token
        $reqToken = $client->post($this->url, [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'body' => json_encode($paramsToken),
        ]);

        $responseToken = $reqToken->getBody();
        $resultToken = json_decode($responseToken, true);

        if ($resultToken['error_code'] != 0) {
            return [
                'error_code' => $resultToken['error_code'],
                'message' => 'Gagal mendapatkan token: ' . $resultToken['message'],
            ];
        }

        $token = $resultToken['data']['token'];

        // Ambil data berdasarkan filter
        $paramsGet = [
            "token" => $token,
            "act"   => $this->actGet,
            "filter" => $this->recordGet,
        ];

        $reqGet = $client->post($this->url, [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'body' => json_encode($paramsGet),
        ]);

        $responseGet = $reqGet->getBody();
        $resultGet = json_decode($responseGet, true);

        if ($resultGet['error_code'] == 0 && count($resultGet['data']) > 0) {
            // Jika data ditemukan, lakukan update
            $updateRecord = $this->record;
            unset($updateRecord['id_prodi']); // Hapus id_prodi dari record jika tidak diperlukan

            $paramsUpdate = [
                "token" => $token,
                "act"   => "UpdatePeriodePerkuliahan",
                "key" => [
                    "id_prodi" => $this->record['id_prodi'],
                    "id_semester" => $this->record['id_semester'],
                ],
                "record" => $updateRecord,
            ];

            $reqUpdate = $client->post($this->url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'body' => json_encode($paramsUpdate),
            ]);

            $responseUpdate = $reqUpdate->getBody();
            $resultUpdate = json_decode($responseUpdate, true);

            return $resultUpdate;
        } else {
            // Jika data tidak ditemukan, lakukan insert
            $paramsInsert = [
                "token" => $token,
                "act"   => $this->act,
                "record" => $this->record,
            ];

            $reqInsert = $client->post($this->url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'body' => json_encode($paramsInsert),
            ]);

            $responseInsert = $reqInsert->getBody();
            $resultInsert = json_decode($responseInsert, true);

            return $resultInsert;
        }
    }


    public function uploadDosenPengajar()
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

                unset($updateRecord['id_aktivitas_mengajar']);

                $paramsUpdate = [
                    "token" => $token,
                    "act"   => "UpdateDosenPengajarKelasKuliah",
                    "key" => [
                        "id_aktivitas_mengajar" => $this->record['id_aktivitas_mengajar']
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

                unset($this->record['id_aktivitas_mengajar']);

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

    public function uploadKrs()
    {
        $token = $this->get_token();
        $paramsGet = [
            "token" => $token,
            "act"   => $this->actGet,
            "filter" => $this->recordGet
        ];

        $result = $this->service_native($paramsGet, $this->url);

        if ($result['error_code'] == 0 && count($result['data']) > 0) {
            return $result;
        }

        $params = [
            "token" => $token,
            "act"   => $this->act,
            "record" => $this->record
        ];

        $result = $this->service_native($params, $this->url);

        return $result;

    }

    public function uploadGeneralNew()
    {
        $token = $this->get_token();
        $paramsGet = [
            "token" => $token,
            "act"   => $this->actGet,
            "filter" => $this->recordGet
        ];

        $result = $this->service_native($paramsGet, $this->url);

        if ($result['error_code'] == 0 && count($result['data']) > 0) {
            $result['data'] = $result['data'][0];
            return $result;
        }

        $params = [
            "token" => $token,
            "act"   => $this->act,
            "record" => $this->record
        ];

        $result = $this->service_native($params, $this->url);

        return $result;
    }

    public function uploadAktivitas()
    {


            $token = $this->get_token();
            $paramsGet = [
                "token" => $token,
                "act"   => $this->actGet,
                "filter" => $this->recordGet
            ];

            $response = $this->service_native($paramsGet, $this->url);

            // $response = $req->getBody();

            $result = $response;

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

                $response = $this->service_native($paramsUpdate, $this->url);

                // $response = $req->getBody();

                $result = $response;

            } else {

                    unset($this->record['id_aktivitas']);

                    $params = [
                        "token" => $token,
                        "act"   => $this->act,
                        "record" => $this->record
                    ];

                    $response = $this->service_native($params, $this->url);

                    $result = $response;
            }

            return $result;

    }

    public function uploadNilaiTransfer()
    {


            $token = $this->get_token();
            $paramsGet = [
                "token" => $token,
                "act"   => $this->actGet,
                "filter" => $this->recordGet
            ];

            $response = $this->service_native($paramsGet, $this->url);

            // $response = $req->getBody();

            $result = $response;

            if ($result['error_code'] == 0 && count($result['data']) > 0) {

                $updateRecord = $this->record;

                unset($updateRecord['id_transfer']);

                $paramsUpdate = [
                    "token" => $token,
                    "act"   => "UpdateNilaiTransferPendidikanMahasiswa",
                    "key" => [
                        "id_transfer" => $this->record['id_transfer']
                    ],
                    "record" => $updateRecord
                ];

                $response = $this->service_native($paramsUpdate, $this->url);

                // $response = $req->getBody();

                $result = $response;

            } else {

                    unset($this->record['id_transfer']);

                    $params = [
                        "token" => $token,
                        "act"   => $this->act,
                        "record" => $this->record
                    ];

                    $response = $this->service_native($params, $this->url);

                    $result = $response;
            }

            return $result;

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

    public function uploadKomponenNew()
    {
        $token = $this->get_token();
        $paramsGet = [
            "token" => $token,
            "act"   => $this->actGet,
            "filter" => $this->recordGet
        ];

        $result = $this->service_native($paramsGet, $this->url);

        if ($result['error_code'] == 0 && count($result['data']) > 0) {

            $params = [
                "token" => $token,
                "act"   => 'UpdateKomponenEvaluasiKelas',
                "key" => [
                    "id_komponen_evaluasi" => $this->record['id_komponen_evaluasi'],
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

            $result = $this->service_native($params, $this->url);

            return $result;
        }

        unset($this->record['id_komponen_evaluasi']);

        $params = [
            "token" => $token,
            "act"   => $this->act,
            "record" => $this->record
        ];

        $result = $this->service_native($params, $this->url);

        return $result;
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
                "act"   => $this->act,
                "token" => $token,
                "record" => [
                    "nilai_komponen_evaluasi" => $this->record['nilai_komponen_evaluasi']
                ],
                "key" => [
                    "id_komponen_evaluasi" => $this->record['id_komponen_evaluasi'],
                    "id_registrasi_mahasiswa" => $this->record['id_registrasi_mahasiswa']
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

    private function get_token()
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

        return $result['data']['token'];
    }

    private function service_native($data,$url,$type='POST') {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_POST, 1);

        $headers = array();
        // $headers[] = 'Authorization: Bearer '.$this->get_token();
        $headers[] = 'Content-Type: application/json';

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
        $data = json_encode($data);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        //curl_setopt($ch, CURLOPT_HEADER, 1);
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_ENCODING, '');
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($ch);

        //print_r($data);
        curl_close($ch);

        return json_decode($result, true);
    }
}
