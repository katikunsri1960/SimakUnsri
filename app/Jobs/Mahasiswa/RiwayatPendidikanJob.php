<?php

namespace App\Jobs\Mahasiswa;

use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Services\Feeder\FeederAPI;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RiwayatPendidikanJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $act;

    public $limit;

    public $offset;

    public $order;

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

        if (isset($response['data']) && ! empty($response['data'])) {

            // chunk data
            $chunks = array_chunk($response['data'], 500);

            foreach ($chunks as $chunk) {

                $chunk = array_map(function ($value) {
                    $value['tanggal_daftar'] = empty($value['tanggal_daftar']) ? null : date('Y-m-d', strtotime($value['tanggal_daftar']));
                    $value['tanggal_keluar'] = empty($value['tanggal_keluar']) ? null : date('Y-m-d', strtotime($value['tanggal_keluar']));

                    return $value;
                }, $chunk);

                RiwayatPendidikan::upsert($chunk, 'id_registrasi_mahasiswa');
            }

        }
    }
}
