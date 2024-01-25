<?php

namespace App\Jobs;

use App\Services\Feeder\FeederAPI;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $act, $limit, $offset, $order, $filter, $model, $primary;

    /**
     * Create a new job instance.
     */
    public function __construct($act, $limit, $offset, $order, $filter, $model, $primary)
    {
        $this->act = $act;
        $this->limit = $limit;
        $this->offset = $offset;
        $this->order = $order;
        $this->filter = $filter;
        $this->model = $model;
        $this->primary = $primary;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $data = new FeederAPI($this->act, $this->offset, $this->limit, $this->order, $this->filter);
        $response = $data->runWS();

        if (!empty($response['data'])) {

            $result = $response['data'];
            $result = array_chunk($result, 1000);

            foreach($result as $r)
            {
                $this->model::upsert($r, $this->primary);
            }

            // PesertaKelasKuliah::upsert($result, ['id_kelas_kuliah', 'id_registrasi_mahasiswa']);
        }
    }
}
