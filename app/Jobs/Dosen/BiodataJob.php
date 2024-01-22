<?php

namespace App\Jobs\Dosen;

use App\Models\Dosen\BiodataDosen;
use App\Services\Feeder\FeederAPI;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class BiodataJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $act, $limit, $offset, $order;
    /**
     * Create a new job instance.
     */
    public function __construct($act, $limit, $offset, $order)
    {
        $this->act = $act;
        $this->limit = $limit;
        $this->offset = $offset;
        $this->order = $order;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $data = new FeederAPI($this->act, $this->offset, $this->limit, $this->order);
        $response = $data->runWS();

        if (isset($response['data']) && !empty($response['data'])) {

            // chunk data
            $chunks = array_chunk($response['data'], 500);

            foreach ($chunks as $chunk) {

                $chunk = array_map(function ($value) {
                    $value['tanggal_lahir'] = empty($value['tanggal_lahir']) ? null : date('Y-m-d', strtotime($value['tanggal_lahir']));
                    return $value;
                }, $chunk);

                BiodataDosen::upsert($chunk, $this->order);
            }

        }
    }
}
