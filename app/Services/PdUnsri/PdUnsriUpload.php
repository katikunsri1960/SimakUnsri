<?php

namespace App\Services\PdUnsri;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PdUnsriUpload extends PdUnsriAPI
{
    protected array $record;

    public function __construct(array $record = [])
    {
        parent::__construct();
        $this->record = $record;
    }

    /**
     * Push / Store Data Prestasi Mahasiswa ke API PDUnsri
     */
    public function uploadPrestasiMahasiswa(): array
    {
        // 1. Ambil Bearer Token
        $token = $this->getToken();

        if (!$token) {
            return [
                'error_code' => 500,
                'error_desc' => 'Gagal mendapatkan token autentikasi dari API PDUnsri.'
            ];
        }

        try {
            // 2. Kirim POST Request ke Endpoint API PDUnsri
            $response = Http::timeout($this->timeout)
                ->connectTimeout($this->connectTimeout)
                ->withToken($token)
                ->acceptJson()
                ->post("{$this->baseUrl}/v1/simak/prestasi-mahasiswa", $this->record);

            $result = $response->json();

            // 3. Tangani Respons Sukses (Status Code 200 / 201)
            if ($response->successful()) {
                return [
                    'error_code' => 0,
                    'message'    => 'Berhasil push data prestasi ke PDUnsri',
                    'data'       => [
                        'id_pdunsri' => $result['data']['id_prestasi'] 
                            ?? ($result['data']['id'] 
                            ?? ($result['id_prestasi'] 
                            ?? $this->record['id_prestasi'] 
                            ?? null))
                    ]
                ];
            }

            // Log Kegagalan Respons HTTP
            Log::warning('API PDUnsri Push Prestasi Gagal Response', [
                'status_code' => $response->status(),
                'payload'     => $this->record,
                'response'    => $result
            ]);

            $errorDesc = $result['message'] ?? 'Gagal menyimpan data prestasi ke PDUnsri.';
            if (isset($result['errors']) && is_array($result['errors'])) {
                $firstError = reset($result['errors']);
                if (is_array($firstError)) {
                    $errorDesc .= ': ' . implode(', ', $firstError);
                }
            }

            return [
                'error_code' => $response->status(),
                'error_desc' => $errorDesc
            ];

        } catch (\Exception $e) {
            Log::error('API PDUnsri Push Prestasi Exception: ' . $e->getMessage(), [
                'payload' => $this->record
            ]);

            return [
                'error_code' => 500,
                'error_desc' => 'Terjadi kesalahan sistem/jaringan: ' . $e->getMessage()
            ];
        }
    }
}