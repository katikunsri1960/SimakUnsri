<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Batchable;
use App\Services\Feeder\FeederAPI;

class ProccessSync implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $model, $act, $limit, $offset, $order, $filter;
    /**
     * Create a new job instance.
     */
    public function __construct($model, $act, $limit, $offset, $order, $filter = null)
    {
        $this->model = $model;
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
        $api = new FeederAPI($this->act, $this->offset, $this->limit, $this->order, $this->filter);

        $result = $api->runWS();

        $model = new {$this->model};

        if ($model::count() == 0) {
            $model::insert($result['data']);
        } else {
            foreach ($result['data'] as $d) {
                $model::updateOrCreate(['id_kurikulum' => $d['id_kurikulum']], $d);
            }
        }
    }
}
