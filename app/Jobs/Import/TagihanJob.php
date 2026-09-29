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

class TagihanJob implements ShouldQueue
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
        $response = $api->getImportTagihan($this->limit, $this->offset);

        if (!isset($response['data']) || empty($response['data'])) {
            Log::warning('Sync tagihan: response kosong/gagal', [
                'offset' => $this->offset,
                'limit' => $this->limit,
            ]);
            return;
        }

        $data = [];

        foreach ($response['data'] as $value) {
            if (empty($value['id_record_tagihan'])) {
                continue;
            }

            $data[] = [
                'id_record_tagihan' => $value['id_record_tagihan'],
                'nomor_pembayaran'  => $value['nomor_pembayaran'] ?? null,
                'nim'               => $value['nim'] ?? null,
                'nama'              => $value['nama'] ?? null,
                'kode_periode'      => $value['kode_periode'] ?? null,
                'total_nilai_tagihan' => $value['total_nilai_tagihan'] ?? 0,
                'status_tagihan'    => $value['status_tagihan'] ?? null,
                'updated_at'        => now(),
            ];
        }

        if (empty($data)) {
            return;
        }

        $chunks = array_chunk($data, 500);

        foreach ($chunks as $chunk) {
            DB::table('import_tagihan')->upsert(
                $chunk,
                ['id_record_tagihan'],
                [
                    'nomor_pembayaran',
                    'nim',
                    'nama',
                    'kode_periode',
                    'total_nilai_tagihan',
                    'status_tagihan',
                    'updated_at'
                ]
            );
        }

        Log::info('Sync tagihan berhasil', [
            'offset' => $this->offset,
            'limit' => $this->limit,
            'total_valid' => count($data),
        ]);
    }
}