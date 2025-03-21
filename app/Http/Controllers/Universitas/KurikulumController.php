<?php

namespace App\Http\Controllers\Universitas;

use App\Models\Perkuliahan\ListKurikulum;
use App\Models\Perkuliahan\MataKuliah;
use App\Services\Feeder\FeederAPI;
use App\Http\Controllers\Controller;
use App\Models\ProgramStudi;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;

class KurikulumController extends Controller
{
    public function index(Request $request)
    {
        $query = ListKurikulum::query();

        if ($request->has('id_prodi')) {
            $validated = $request->validate([
                'id_prodi' => 'array',
                'id_prodi.*' => 'exists:program_studis,id_prodi'
            ]);

            $query->whereIn('id_prodi', $validated['id_prodi']);
        }

        $data = $query->get();

        $prodi = ProgramStudi::orderBy('kode_program_studi')->get();
        return view('universitas.kurikulum.list-kurikulum', [
            'data' => $data,
            'prodi' => $prodi,
        ]);
    }

    public function detail_kurikulum(ListKurikulum $kurikulum)
    {
        $data = $kurikulum->load('matkul_kurikulum');

        return view('universitas.kurikulum.detail-kurikulum', [
            'data' => $data,
        ]);
    }

    private function sync($act, $limit, $offset, $order, $job, $name, $model, $primary)
    {
        $prodi = ProgramStudi::pluck('id_prodi')->toArray();
        $semester = Semester::pluck('id_semester')->toArray();
        $semester = array_chunk($semester, 4);
        $semester = array_map(function ($value) {
            return "id_semester IN ('" . implode("','", $value) . "')";
        }, $semester);

        $batch = Bus::batch([])->name($name)->dispatch();

        foreach ($prodi as $p) {
            foreach ($semester as $s) {
                $filter = "id_prodi = '$p' AND $s";
                // dd($filter);
                $batch->add(new $job($act, $limit, $offset, $order, $filter, $model, $primary));
            }
        }

        return $batch;
    }

    private function sync3($act, $limit, $offset, $order, $job, $name, $model, $primary, $reference, $id)
    {
        $reference = $reference::pluck($id)->toArray();
        $reference = array_chunk($reference, 40);

        $filter = array_map(function ($value) use ($id) {
            return "$id IN ('" . implode("','", $value) . "')";
        }, $reference);

        $batch = Bus::batch([])->name($name)->dispatch();

        foreach ($filter as $f) {
            $batch->add(new $job($act, $limit, $offset, $order, $f, $model, $primary));
        }

        return $batch;

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
        if (ProgramStudi::count() == 0 || Semester::count() == 0 || MataKuliah::count() == 0) {
            return redirect()->back()->with('error', 'Data Program Studi, Semester atau Matakuliah Kosong, Harap Sinkronkan Terlebih dahulu data Referensi!');
        }

        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '1G');

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

        $this->sync('GetMatkulKurikulum', '0', '0', '', \App\Jobs\SyncJob::class, 'matkul-kurikulum', \App\Models\Perkuliahan\MatkulKurikulum::class, ['id_kurikulum', 'id_matkul']);

        return redirect()->route('univ.kurikulum')->with('success', 'Data kurikulum berhasil disinkronisasi');

    }

    public function push_data_kurikulum()
    {

    }

    public function sync_mata_kuliah()
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '1G');

        $act = "GetDetailMataKuliah";
        $count = "GetCountMataKuliah";
        $limit = 1000;
        $offset = 0;
        $order = '';

        $api = new FeederAPI($count,$offset, $limit, $order);

        $result = $api->runWS();
        $total = $result['data'];
        $job = \App\Jobs\SyncJob::class;

        $batch = Bus::batch([])->dispatch();
        $order = 'id_matkul';
        $filter = '';
        $model = \App\Models\Perkuliahan\MataKuliah::class;
        $primary = 'id_matkul';

        for ($i = 0; $i < $total; $i += $limit) {
            $batch->add(new $job($act, $limit, $i, $order, $filter,$model, $primary));
        }

        return redirect()->route('univ.mata-kuliah')->with('success', 'Data mata kuliah berhasil disinkronisasi');
    }

    public function sync_rencana()
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '1G');

        $data = [
            [
                'act' => 'GetListRencanaPembelajaran',
                'limit' => '',
                'offset' => '',
                'order' => '',
                'job' => \App\Jobs\SyncJob::class,
                'name' => 'rencana-pembelajaran',
                'model' => \App\Models\Perkuliahan\RencanaPembelajaran::class,
                'primary' => 'id_rencana_ajar',
                'reference' => \App\Models\Perkuliahan\MataKuliah::class,
                'id' => 'id_matkul'
            ],
            [
                'act' => 'GetListRencanaEvaluasi',
                'limit' => '',
                'offset' => '',
                'order' => '',
                'job' => \App\Jobs\SyncJob::class,
                'name' => 'rencana-evaluasi',
                'model' => \App\Models\Perkuliahan\RencanaEvaluasi::class,
                'primary' => 'id_rencana_ajar',
                'reference' => \App\Models\Perkuliahan\MataKuliah::class,
                'id' => 'id_matkul'
            ],
        ];

        foreach ($data as $d) {
            $batch = $this->sync3($d['act'], $d['limit'], $d['offset'], $d['order'], $d['job'], $d['name'], $d['model'], $d['primary'], $d['reference'], $d['id']);
        }

        return redirect()->back()->with('success', 'Sinkronisasi Aktivitas Mahasiswa Berhasil!');
    }

    public function is_active(ListKurikulum $kurikulum)
    {
        $kurikulum->is_active = !$kurikulum->is_active;
        $kurikulum->save();

        return redirect()->back()->with('success', 'Status Kurikulum Berhasil Diubah');
    }
}
