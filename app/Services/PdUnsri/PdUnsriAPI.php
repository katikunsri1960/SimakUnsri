<?php

namespace App\Services\PdUnsri;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PdUnsriAPI
{
    protected string $baseUrl;
    protected int $timeout;
    protected int $connectTimeout;

    public function __construct()
    {
        $this->baseUrl = rtrim(
            config('services.api_pd_unsri.base_url', ''),
            '/'
        );

        $this->timeout = (int) config(
            'services.api_pd_unsri.timeout',
            30
        );

        $this->connectTimeout = (int) config(
            'services.api_pd_unsri.connect_timeout',
            10
        );
    }

    public function getToken(): ?string
    {
        return Cache::remember('api_pd_unsri:token', now()->addMinutes(50), function () {
            $response = Http::timeout($this->timeout)
                ->connectTimeout($this->connectTimeout)
                ->post("{$this->baseUrl}/get-token?" . http_build_query([
                    'email' => config('services.api_pd_unsri.username'),
                    'password' => config('services.api_pd_unsri.password'),
                ]));

            if ($response->failed()) {
                Log::warning('API PD Unsri login gagal', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return null;
            }

            return $response->json('token');
        });
    }

    public function getListJabatanFungsional(int $limit = 100, int $offset = 0): ?array
    {
        $token = $this->getToken();

        if (!$token) {
            return null;
        }

        $response = Http::timeout($this->timeout)
            ->connectTimeout($this->connectTimeout)
            ->withToken($token)
            ->get("{$this->baseUrl}/v1/sister-list-jabatan-fungsional", [
                'limit' => $limit,
                'offset' => $offset,
            ]);

        if ($response->failed()) {
            Log::warning('API PD Unsri gagal ambil list jabatan fungsional', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return null;
        }

        return $response->json();
    }
}