<?php

namespace App\Jobs\PdUnsri;

use App\Services\PdUnsri\PdUnsriAPI;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ListJabatanFungsionalJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $limit, $offset;

    public function __construct($limit, $offset)
    {
        $this->limit = $limit;
        $this->offset = $offset;
    }

    public function handle(PdUnsriAPI $api): void
    {
        $response = $api->getListJabatanFungsional(
            $this->limit,
            $this->offset
        );

        if (!isset($response['data']) || empty($response['data'])) {
            Log::warning('Sync jabatan fungsional: response kosong/gagal', [
                'offset' => $this->offset,
                'limit' => $this->limit,
            ]);

            return;
        }

        $data = [];

        foreach ($response['data'] as $value) {

            /*
             * Data error dari API biasanya tidak memiliki
             * id dan id_sdm.
             */
            if (empty($value['id']) || empty($value['id_sdm'])) {

                Log::warning('Sync jabatan fungsional: data dilewati', [
                    'offset' => $this->offset,
                    'id' => $value['id'] ?? null,
                    'id_sdm' => $value['id_sdm'] ?? null,
                    'detail' => $value['detail'] ?? null,
                    'message' => $value['message'] ?? null,
                ]);

                continue;
            }

            /*
             * Konversi tanggal_mulai ke format database.
             */
            $tanggalMulai = null;

            if (!empty($value['tanggal_mulai'])) {
                $timestamp = strtotime($value['tanggal_mulai']);

                if ($timestamp !== false) {
                    $tanggalMulai = date('Y-m-d', $timestamp);
                }
            }

            /*
             * Hanya masukkan kolom yang memang
             * digunakan oleh tabel database.
             */
            $data[] = [
                'id' => $value['id'],
                'id_sdm' => $value['id_sdm'],
                'id_stat_pegawai' => $value['id_stat_pegawai'] ?? null,
                'jabatan_fungsional' => $value['jabatan_fungsional'] ?? null,
                'nm_stat_pegawai' => $value['nm_stat_pegawai'] ?? null,
                'sk' => $value['sk'] ?? null,
                'tanggal_mulai' => $tanggalMulai,
            ];
        }

        /*
         * Tidak ada data valid yang perlu disimpan.
         */
        if (empty($data)) {
            Log::info('Sync jabatan fungsional: tidak ada data valid', [
                'offset' => $this->offset,
                'limit' => $this->limit,
                'total_api' => count($response['data']),
            ]);

            return;
        }

        /*
         * Tetap gunakan chunk agar aman untuk jumlah data besar.
         */
        $chunks = array_chunk($data, 500);

        foreach ($chunks as $chunk) {

            DB::table('sister_list_jabatan_fungsional')->upsert(
                $chunk,
                ['id'],
                [
                    'id_sdm',
                    'id_stat_pegawai',
                    'jabatan_fungsional',
                    'nm_stat_pegawai',
                    'sk',
                    'tanggal_mulai',
                ]
            );
        }

        Log::info('Sync jabatan fungsional berhasil', [
            'offset' => $this->offset,
            'limit' => $this->limit,
            'total_api' => count($response['data']),
            'total_valid' => count($data),
            'total_dilewati' => count($response['data']) - count($data),
        ]);
    }
}