<?php

namespace App\Jobs\Perkuliahan;

use App\Services\Feeder\FeederAPI;
use App\Models\Perkuliahan\DosenPengajarKelasKuliah;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PengajarKelasJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $act, $limit, $offset, $order, $filter;
    /**
     * Create a new job instance.
     */
    public function __construct($act, $limit, $offset, $order, $filter)
    {
        $this->act = $act;
        $this->limit = $limit;
        $this->offset = $offset;
        $this->order = $order;
        $this->filter = $filter;
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

            $result = array_chunk($result, 100);

            foreach ($result as $r) {
                DosenPengajarKelasKuliah::upsert($r, 'id_aktivitas_mengajar');
            }

        }
    }
}
