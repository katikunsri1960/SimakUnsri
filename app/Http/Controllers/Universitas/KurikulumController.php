<?php

namespace App\Http\Controllers\Universitas;

use App\Models\ListKurikulum;
use App\Models\MataKuliah;
use App\Services\Feeder\FeederAPI;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;

class KurikulumController extends Controller
{
    public function index()
    {
        $data = ListKurikulum::all();

        return view('universitas.kurikulum.list-kurikulum', [
            'data' => $data,
        ]);
    }

    public function matkul()
    {
        $data = MataKuliah::all();

        return view('universitas.mata-kuliah.index', [
            'data' => $data,
        ]);
    }

    public function sync_kurikulum()
    {
        $act = "GetListKurikulum";
        $limit = 0;
        $offset = 0;
        $order = "";
        $model = \App\Models\ListKurikulum::class;

        $batch = Bus::batch([])->dispatch();
        $job = new ProccessSync($model, $act, $limit, $offset, $order);

        return redirect()->route('univ.kurikulum')->with('success', 'Data kurikulum berhasil disinkronisasi');

    }

    public function push_data_kurikulum()
    {

    }

    public function sync_mata_kuliah()
    {
        $act = "GetDetailMataKuliah";
        $limit = 100;
        $offset = 0;
        $order = "";

        $api = new FeederAPI($act,$offset, $limit, $order);

        $result = $api->runWS();
        dd($result);

        foreach ($result['data'] as $d) {
            MataKuliah::updateOrCreate(['id_matkul' => $d['id_matkul']], $d);
        }


        return redirect()->route('univ.mata-kuliah')->with('success', 'Data mata kuliah berhasil disinkronisasi');
    }
}
