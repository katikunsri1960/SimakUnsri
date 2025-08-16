<?php

namespace App\Jobs\Mahasiswa;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Mahasiswa\BiodataMahasiswa;
use App\Services\Feeder\FeederAPI;
use Illuminate\Bus\Batchable;// Import the Log class

class BiodataJob implements ShouldQueue
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

            // chunk data
            $chunks = array_chunk($response['data'], 100);

            foreach ($chunks as $chunk) {
                BiodataMahasiswa::upsert($chunk, 'id_mahasiswa');
            }

        }
    }
}
