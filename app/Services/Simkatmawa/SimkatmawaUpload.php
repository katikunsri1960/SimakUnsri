<?php

namespace App\Services\Simkatmawa;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class SimkatmawaUpload
{
    protected $baseUrl;
    protected $email;
    protected $password;
    protected $record;
    protected $recordGet;

    public function __construct($record = [], $recordGet = null)
    {
        $this->baseUrl   = rtrim(env('SIMKATMAWA_URL', 'https://simkatmawa.kemdiktisaintek.go.id'), '/');
        $this->email     = env('SIMKATMAWA_EMAIL');
        $this->password  = env('SIMKATMAWA_PASSWORD');
        $this->record    = $record;
        $this->recordGet = $recordGet;
    }

    /**
     * Mengirim / Memperbarui Data Prestasi
     */
    public function uploadPrestasiMahasiswa()
    {
        $client = new Client(['http_errors' => false]);

        // 1. Dapatkan Bearer Token
        $token = $this->get_token();

        if (!$token) {
            return [
                'error_code' => 500,
                'error_desc' => 'Gagal mendapatkan Bearer Token / Otentikasi SIMKATMAWA.'
            ];
        }

        try {
            // 2. Cek apakah data prestasi sudah ada di SIMKATMAWA (Diubah dari /api/v1/prestasi ke /api/prestasi)
            $resGet = $client->get("{$this->baseUrl}/api/prestasi", [
                'headers' => [
                    'Accept'        => 'application/json',
                    'Authorization' => 'Bearer ' . $token,
                ],
                'query' => [
                    'nim' => $this->record['nim'] ?? null,
                ]
            ]);

            $resultGet = json_decode($resGet->getBody(), true);

            // 3. Jika Data Sudah Ada -> Update (PUT)
            if ($resGet->getStatusCode() == 200 && !empty($resultGet['data'])) {
                $existingId = $resultGet['data'][0]['id'] ?? null;

                $resUpdate = $client->put("{$this->baseUrl}/api/prestasi/{$existingId}", [
                    'headers' => [
                        'Content-Type'  => 'application/json',
                        'Accept'        => 'application/json',
                        'Authorization' => 'Bearer ' . $token,
                    ],
                    'json' => $this->record
                ]);

                $resultUpdate = json_decode($resUpdate->getBody(), true);

                if ($resUpdate->getStatusCode() == 200) {
                    return [
                        'error_code' => 0,
                        'message'    => 'Berhasil Update Data SIMKATMAWA',
                        'data'       => ['id_simkatmawa' => $existingId]
                    ];
                }

                return [
                    'error_code' => $resUpdate->getStatusCode(),
                    'error_desc' => $resultUpdate['message'] ?? 'Gagal Update Data ke SIMKATMAWA'
                ];
            }

            // 4. Jika Data Belum Ada -> Store Baru (POST ke /api/prestasi)
            $resInsert = $client->post("{$this->baseUrl}/api/prestasi-mandiri", [
                'headers' => [
                    'Content-Type'  => 'application/json',
                    'Accept'        => 'application/json',
                    'Authorization' => 'Bearer ' . $token,
                ],
                'json' => $this->record
            ]);

            $resultInsert = json_decode($resInsert->getBody(), true);

            if (in_array($resInsert->getStatusCode(), [200, 201])) {
                return [
                    'error_code' => 0,
                    'message'    => 'Berhasil Insert Data SIMKATMAWA',
                    'data'       => ['id_simkatmawa' => $resultInsert['data']['id'] ?? ($resultInsert['id'] ?? null)]
                ];
            }

            // Catat log jika validasi/insert ditolak server
            Log::warning('SIMKATMAWA Store Error Response: ', [
                'code' => $resInsert->getStatusCode(),
                'body' => $resultInsert
            ]);

            return [
                'error_code' => $resInsert->getStatusCode(),
                'error_desc' => $resultInsert['message'] ?? (json_encode($resultInsert) ?? 'Gagal Insert Data ke SIMKATMAWA')
            ];

        } catch (\Exception $e) {
            Log::error('SIMKATMAWA Upload Exception: ' . $e->getMessage());
            return [
                'error_code' => 500,
                'error_desc' => 'Terjadi kesalahan sistem/jaringan: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Request Token Auth (POST /api/login)
     */
    private function get_token()
    {
        try {
            $client = new Client(['http_errors' => false]);

            $response = $client->post("{$this->baseUrl}/api/login", [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept'       => 'application/json',
                ],
                'json' => [
                    'email'    => $this->email,
                    'password' => $this->password,
                ]
            ]);

            $result = json_decode($response->getBody(), true);

            Log::info('SIMKATMAWA Login Response: ', [
                'status_code' => $response->getStatusCode(),
                'body'        => $result,
                'email'       => $this->email
            ]);

            if ($response->getStatusCode() == 200) {
                return $result['token'] 
                    ?? ($result['data']['token'] 
                    ?? ($result['access_token'] 
                    ?? null));
            }

            return null;

        } catch (\Exception $e) {
            Log::error('SIMKATMAWA Login Exception: ' . $e->getMessage());
            return null;
        }
    }
}