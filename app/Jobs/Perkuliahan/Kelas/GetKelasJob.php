<?php

namespace App\Jobs\Perkuliahan\Kelas;

use App\Models\KelasKuliah;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\Feeder\FeederAPI;

class GetKelasJob implements ShouldQueue
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

        if (isset($response['data']) && !empty($response['data'])) {

            $data = $response['data'];
            
            $data = array_map(function ($value) {
                $value['tanggal_mulai_efektif'] = empty($value['tanggal_mulai_efektif']) ? null : date('Y-m-d', strtotime($value['tanggal_mulai_efektif']));
                $value['tanggal_akhir_efektif'] = empty($value['tanggal_akhir_efektif']) ? null : date('Y-m-d', strtotime($value['tanggal_akhir_efektif']));
                return $value;
            }, $data);

            KelasKuliah::upsert($data, 'id_kelas_kuliah');
        }
    }
}
