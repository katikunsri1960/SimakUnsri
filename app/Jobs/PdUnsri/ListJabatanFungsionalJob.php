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
        $response = $api->getListJabatanFungsional($this->limit, $this->offset);

        if (isset($response['data']) && !empty($response['data'])) {

            $chunks = array_chunk($response['data'], 500);

            foreach ($chunks as $chunk) {

                $chunk = array_map(function ($value) {
                    $value['tanggal_mulai'] = empty($value['tanggal_mulai'])
                        ? null
                        : date('Y-m-d', strtotime($value['tanggal_mulai']));
                    return $value;
                }, $chunk);

                DB::table('sister_list_jabatan_fungsional')->upsert(
                    $chunk,
                    'id',
                    ['id_sdm', 'id_stat_pegawai', 'jabatan_fungsional', 'nm_stat_pegawai', 'sk', 'tanggal_mulai']
                );
            }
        } else {
            Log::warning('Sync jabatan fungsional: response kosong/gagal', [
                'offset' => $this->offset,
                'limit' => $this->limit,
            ]);
        }
    }
}