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


        foreach ($result['data'] as $d) {

            $tanggal_mulai_efektif = isset($d['tanggal_mulai_efektif']) ? \Carbon\Carbon::parse($d['tanggal_mulai_efektif'])->format('Y-m-d') : null;
            $tanggal_akhir_efektif = isset($d['tanggal_akhir_efektif']) ? \Carbon\Carbon::parse($d['tanggal_akhir_efektif'])->format('Y-m-d') : null;

            $this->model::updateOrCreate(['id_matkul' => $d['id_matkul']], [
                'kode_mata_kuliah' => $d['kode_mata_kuliah'],
                'nama_mata_kuliah' => $d['nama_mata_kuliah'],
                'id_prodi' => $d['id_prodi'],
                'id_jenis_mata_kuliah' => $d['id_jenis_mata_kuliah'],
                'id_kelompok_mata_kuliah' => $d['id_kelompok_mata_kuliah'],
                'sks_mata_kuliah' => $d['sks_mata_kuliah'],
                'sks_tatap_muka' => $d['sks_tatap_muka'],
                'sks_praktek' => $d['sks_praktek'],
                'sks_praktek_lapangan' => $d['sks_praktek_lapangan'],
                'sks_simulasi' => $d['sks_simulasi'],
                'metode_kuliah' => $d['metode_kuliah'],
                'ada_sap' => $d['ada_sap'],
                'ada_silabus' => $d['ada_silabus'],
                'ada_bahan_ajar' => $d['ada_bahan_ajar'],
                'ada_acara_praktek' => $d['ada_acara_praktek'],
                'ada_diktat' => $d['ada_diktat'],
                'tanggal_mulai_efektif' => $tanggal_mulai_efektif,
                'tanggal_akhir_efektif' => $tanggal_akhir_efektif,
            ]);
        }
    }
}
