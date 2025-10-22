<?php

namespace App\Jobs\Perkuliahan;

use App\Models\Perkuliahan\PesertaKelasKuliah;
use App\Services\Feeder\FeederAPI;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PesertaKelasJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 300; // ⏱ allow up to 5 minutes
    public $tries = 3;     // retry up to 3 times before failing

    public $act, $limit, $offset, $order, $filter;

    public function __construct($act, $limit, $offset, $order, $filter)
    {
        $this->act = $act;
        $this->limit = $limit;
        $this->offset = $offset;
        $this->order = $order;
        $this->filter = $filter;
    }

    public function handle(): void
    {
        $data = new FeederAPI(
            $this->act,
            $this->offset,
            $this->limit,
            $this->order,
            $this->filter
        );

        $response = $data->runWS();

        if (!empty($response['data'])) {
            foreach (array_chunk($response['data'], 1000) as $chunk) {
                PesertaKelasKuliah::upsert($chunk, [
                    'id_kelas_kuliah',
                    'id_registrasi_mahasiswa'
                ]);
            }
        }
    }
}

