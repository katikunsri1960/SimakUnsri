<?php

namespace App\Jobs\Dosen;

use App\Models\Dosen\PenugasanDosen;
use App\Services\Feeder\FeederAPI;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PenugasanDosenJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $act, $limit, $offset, $order, $filter;
    /**
     * Create a new job instance.
     */
    public function __construct($act, $limit, $offset, $order, $filter = null)
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

        if (isset($response['data']) && !empty($response['data'])) {

            $data = $response['data'];

            PenugasanDosen::upsert($data, ['id_tahun_ajaran', 'id_registrasi_dosen']);
        }
    }
}
