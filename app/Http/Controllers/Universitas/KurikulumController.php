<?php

namespace App\Http\Controllers\Universitas;

use App\Models\Perkuliahan\ListKurikulum;
use App\Models\Perkuliahan\MataKuliah;
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

        $query = MataKuliah::with('prodi');

        if ($searchValue) {
            $query = $query->where('kode_mata_kuliah', 'like', '%' . $searchValue . '%')
                ->orWhere('nama_mata_kuliah', 'like', '%' . $searchValue . '%');
        }

        if ($request->has('prodi') && !empty($request->prodi)) {
            $filter = $request->prodi;
            $query->whereIn('id_prodi', $filter);
        }

        $recordsFiltered = $query->count();

        $limit = $request->input('length');
        $offset = $request->input('start');

        // Define the column names that correspond to the DataTables column indices
        if ($request->has('order')) {
            $orderColumn = $request->input('order.0.column');
            $orderDirection = $request->input('order.0.dir');

            // Define the column names that correspond to the DataTables column indices
            $columns = ['kode_mata_kuliah', 'nama_mata_kuliah', 'sks_mata_kuliah', 'prodi'];

            if ($columns[$orderColumn] == 'prodi') {
                $query = $query->join('program_studis as prodi', 'mata_kuliahs.id_prodi', '=', 'prodi.id')
                    ->orderBy('prodi.nama_jenjang_pendidikan', $orderDirection)
                    ->orderBy('prodi.nama_program_studi', $orderDirection)
                    ->select('mata_kuliahs.*', 'prodi.nama_jenjang_pendidikan', 'prodi.nama_program_studi'); // Avoid column name conflicts
            } else {
                $query = $query->orderBy($columns[$orderColumn], $orderDirection);
            }
        }

        $data = $query->skip($offset)->take($limit)->get();

        $recordsTotal = Matakuliah::count();

        $response = [
            'draw' => $request->input('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ];

        return response()->json($response);

    }

    public function sync_kurikulum()
    {
        $act = "GetListKurikulum";
        $count = "GetCountKurikulum";
        $limit = 1000;
        $offset = 0;
        $order = "";
        $model = \App\Models\Perkuliahan\ListKurikulum::class;

        $api = new FeederAPI($count,$offset, $limit, $order);

        $result = $api->runWS();
        // dd($result['data']);
        $total = $result['data'];

        for($i = 0; $i < $total; $i += $limit) {
            $api = new FeederAPI($act,$i, $limit, $order);
            $result = $api->runWS();

            $chunk = array_chunk($result['data'], 100);

            foreach ($chunk as $c) {
                $model::upsert($c, 'id_kurikulum');
            }

        }

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
        $order = '';

        $api = new FeederAPI($count,$offset, $limit, $order);

        $result = $api->runWS();
        // dd($result['data']);
        $total = $result['data'];

        $batch = Bus::batch([])->dispatch();
        $order = 'id_matkul';

        for ($i = 0; $i < $total; $i += $limit) {
            $job = new ProccessSync(\App\Models\Perkuliahan\MataKuliah::class, $act, $limit, $i, $order);
            $batch->add($job);
        }

        return redirect()->route('univ.mata-kuliah')->with('success', 'Data mata kuliah berhasil disinkronisasi');
    }
}
