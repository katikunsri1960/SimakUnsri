<?php

namespace App\Jobs;

use App\Models\SyncError;
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
        ini_set('memory_limit', '2048M');

        $data = new FeederAPI($this->act, $this->offset, $this->limit, $this->order, $this->filter);
        $response = $data->runWS();

        if (!empty($response['data'])) {

            $result = $response['data'];
            $result = array_chunk($result, 500);

            foreach($result as $r)
            {
                if($this->act == 'GetListMahasiswaLulusDO')
                {
                    $r = array_map(function ($value) {
                        $value['tgl_masuk_sp'] = empty($value['tgl_masuk_sp']) ? null : date('Y-m-d', strtotime($value['tgl_masuk_sp']));
                        $value['tgl_keluar'] = empty($value['tgl_keluar']) ? null : date('Y-m-d', strtotime($value['tgl_keluar']));
                        $value['tgl_create'] = empty($value['tgl_create']) ? null : date('Y-m-d', strtotime($value['tgl_create']));
                        $value['tgl_sk_yudisium'] = empty($value['tgl_sk_yudisium']) ? null : date('Y-m-d', strtotime($value['tgl_sk_yudisium']));
                        return $value;
                    }, $r);
                }

                if ($this->act == 'GetListAktivitasMahasiswa') {
                    $r = array_map(function ($value) {
                        $value['tanggal_sk_tugas'] = empty($value['tanggal_sk_tugas']) ? null : date('Y-m-d', strtotime($value['tanggal_sk_tugas']));
                        $value['tanggal_mulai'] = empty($value['tanggal_mulai']) ? null : date('Y-m-d', strtotime($value['tanggal_mulai']));
                        $value['tanggal_selesai'] = empty($value['tanggal_selesai']) ? null : date('Y-m-d', strtotime($value['tanggal_selesai']));
                        return $value;
                    }, $r);
                }

                if ($this->act == 'GetRiwayatFungsionalDosen') {
                    $r = array_map(function ($value) {
                        $value['mulai_sk_jabatan'] = empty($value['mulai_sk_jabatan']) ? null : date('Y-m-d', strtotime($value['mulai_sk_jabatan']));
                        return $value;
                    }, $r);
                }

                if ($this->act == 'GetDetailKelasKuliah') {
                    $r = array_map(function ($value) {
                        $value['tanggal_mulai_efektif'] = empty($value['tanggal_mulai_efektif']) ? null : date('Y-m-d', strtotime($value['tanggal_mulai_efektif']));
                        $value['tanggal_akhir_efektif'] = empty($value['tanggal_akhir_efektif']) ? null : date('Y-m-d', strtotime($value['tanggal_akhir_efektif']));
                        return $value;
                    }, $r);
                }

                if ($this->act == 'GetDetailMataKuliah') {
                    $r = array_map(function ($value) {
                        $value['tanggal_mulai_efektif'] = empty($value['tanggal_mulai_efektif']) ? null : date('Y-m-d', strtotime($value['tanggal_mulai_efektif']));
                        $value['tanggal_selesai_efektif'] = empty($value['tanggal_selesai_efektif']) ? null : date('Y-m-d', strtotime($value['tanggal_selesai_efektif']));
                        return $value;
                    }, $r);
                }

                try {

                    $this->model::upsert($r, $this->primary);

                } catch (\Throwable $th) {

                    foreach($r as $row)
                    {
                        try {
                            $conditions = is_array($this->primary)
                                ? array_combine($this->primary, array_map(fn($key) => $row[$key], $this->primary))
                                : [$this->primary => $row[$this->primary]];

                            $this->model::updateOrCreate($conditions, $row);

                        } catch (\Throwable $th) {

                            SyncError::create([
                                'model' => $this->model,
                                'message' => $th->getMessage()
                            ]);

                            continue;
                        }
                    }

                    continue;
                }

            }

        }
    }
}
