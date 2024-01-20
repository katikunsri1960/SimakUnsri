<?php

namespace App\Http\Controllers\Universitas;

use App\Http\Controllers\Controller;
use App\Models\Dosen\BiodataDosen;
use Illuminate\Http\Request;
use App\Services\Feeder\FeederAPI;
use Illuminate\Support\Facades\Bus;

class DosenController extends Controller
{
    private function count_value($act)
    {
        $data = new FeederAPI($act,0,0, '');
        $response = $data->runWS();
        $count = $response['data'];

        return $count;
    }

    public function dosen()
    {
        $db = new BiodataDosen();
        $data = $db->list_dosen();

        return view('universitas.dosen.index', [
            'data' => $data,
        ]);
    }

    public function sync_dosen()
    {
        $data = [
            ['act' => 'DetailBiodataDosen', 'count' => 'GetCountDosen', 'primary' => 'id_dosen', 'job' => \App\Jobs\Dosen\BiodataJob::class]
        ];

        $batch = Bus::batch([])->dispatch();

        foreach ($data as $d) {

            $count = $this->count_value($d['count']);

            $limit = 1000;
            $act = $d['act'];
            $order = $d['primary'];

            for ($i=0; $i < $count; $i+=$limit) {
                $job = new $d['job']($act, $limit, $i, $order);
                $batch->add($job);
            }

        }

        return redirect()->back()->with('success', 'Sinkronisasi Data Dosen Berhasil!');
    }
}
