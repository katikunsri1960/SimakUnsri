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
    public function __construct($act, $limit, $offset, $order, $filter = null, $model, $primary)
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
            $result = array_chunk($result, 500);

            foreach($result as $r)
            {
                if ($this->act == 'GetListAktivitasMahasiswa') {
                    $r = array_map(function ($value) {
                        $value['tanggal_sk_tugas'] = empty($value['tanggal_sk_tugas']) ? null : date('Y-m-d', strtotime($value['tanggal_sk_tugas']));
                        $value['tanggal_mulai'] = empty($value['tanggal_mulai']) ? null : date('Y-m-d', strtotime($value['tanggal_mulai']));
                        $value['tanggal_selesai'] = empty($value['tanggal_selesai']) ? null : date('Y-m-d', strtotime($value['tanggal_selesai']));
                        return $value;
                    }, $r);
                }

                $this->model::upsert($r, $this->primary);
            }

        }
    }
}
