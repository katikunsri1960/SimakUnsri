<?php

namespace App\Jobs\Import;

use App\Services\PdUnsri\PdUnsriAPI;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PembayaranJob implements ShouldQueue
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
        $response = $api->getImportPembayaran($this->limit, $this->offset);

        if (!isset($response['data']) || empty($response['data'])) {
            Log::warning('Sync pembayaran: response kosong/gagal', [
                'offset' => $this->offset,
                'limit' => $this->limit,
            ]);
            return;
        }

        $data = [];

        foreach ($response['data'] as $value) {
            if (empty($value['id_record_pembayaran'])) {
                continue;
            }

            $data[] = [
                'id_record_pembayaran' => $value['id_record_pembayaran'],
                'id_record_tagihan'    => $value['id_record_tagihan'] ?? null,
                'nomor_pembayaran'     => $value['nomor_pembayaran'] ?? null,
                'nim'                  => $value['nim'] ?? null,
                'nominal_pembayaran'   => $value['nominal_pembayaran'] ?? 0,
                'tgl_pembayaran'       => $value['tgl_pembayaran'] ?? null,
                'updated_at'           => now(),
            ];
        }

        if (empty($data)) {
            return;
        }

        $chunks = array_chunk($data, 500);

        foreach ($chunks as $chunk) {
            DB::table('import_pembayaran')->upsert(
                $chunk,
                ['id_record_pembayaran'],
                [
                    'id_record_tagihan',
                    'nomor_pembayaran',
                    'nim',
                    'nominal_pembayaran',
                    'tgl_pembayaran',
                    'updated_at'
                ]
            );
        }

        Log::info('Sync pembayaran berhasil', [
            'offset' => $this->offset,
            'limit' => $this->limit,
            'total_valid' => count($data),
        ]);
    }
}