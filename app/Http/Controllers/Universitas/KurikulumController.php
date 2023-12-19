<?php

namespace App\Http\Controllers\Universitas;

use App\Models\ListKurikulum;
use App\Models\MataKuliah;
use App\Services\Feeder\FeederAPI;
use App\Jobs\ProccessSync;
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
        return view('universitas.mata-kuliah.index');
    }

    public function matkul_data(Request $request)
    {
        $searchValue = $request->input('search.value');

        $query = MataKuliah::query();

        if ($searchValue) {
            $query = $query->where('kode_mata_kuliah', 'like', '%' . $searchValue . '%')
                ->orWhere('nama_mata_kuliah', 'like', '%' . $searchValue . '%');
        }

        if ($request->has('prodi') && !empty($request->prodi)) {
            $filter = $request->prodi;
            $query->whereIn('id_prodi', $filter);
        }

        $limit = $request->input('length');
        $offset = $request->input('start');
        // $order = $request->input('order.0.column');
        // $dir = $request->input('order.0.dir');

        $query->skip($offset)->take($limit);

        $data = $query->get();


        $recordsFiltered = $data->count();
        $recordsTotal = Matakuliah::count();

        $response = [
            'draw' => $request->input('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ];
        // dd($response);
        return response()->json($response);

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
        $count = "GetCountMataKuliah";
        $limit = 1000;
        $offset = 0;
        $order = "";

        $api = new FeederAPI($count,$offset, $limit, $order);

        $result = $api->runWS();

        $total = $result['data'];

        $batch = Bus::batch([])->dispatch();

        for ($i = 0; $i < $total; $i += $limit) {
            $job = new ProccessSync(\App\Models\MataKuliah::class, $act, $limit, $i, $order);
            $batch->add($job);
        }

        return redirect()->route('univ.mata-kuliah')->with('success', 'Data mata kuliah berhasil disinkronisasi');
    }
}
