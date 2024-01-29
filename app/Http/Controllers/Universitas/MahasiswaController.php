<?php

namespace App\Http\Controllers\Universitas;

use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Mahasiswa\BiodataMahasiswa;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Feeder\FeederAPI;
use Illuminate\Support\Facades\Bus;

class MahasiswaController extends Controller
{
    public function daftar_mahasiswa()
    {
        return view('universitas.mahasiswa.index');
    }

    private function count_value($act)
    {
        $data = new FeederAPI($act,0,0, '');
        $response = $data->runWS();
        $count = $response['data'];

        return $count;
    }

    public function sync_mahasiswa()
    {
        $data = [
            ['act' => 'GetBiodataMahasiswa', 'count' => 'GetCountBiodataMahasiswa', 'order' => 'id_mahasiswa', 'job' => \App\Jobs\Mahasiswa\BiodataJob::class],
            ['act' => 'GetListRiwayatPendidikanMahasiswa', 'count' => 'GetCountRiwayatPendidikanMahasiswa', 'order' => 'id_registrasi_mahasiswa', 'job' => \App\Jobs\Mahasiswa\RiwayatPendidikanJob::class]
        ];

        $batch = Bus::batch([])->dispatch();

        foreach ($data as $d) {

            $count = $this->count_value($d['count']);

            $limit = 1000;
            $act = $d['act'];
            $order = $d['order'];

            for ($i=0; $i < $count; $i+=$limit) {
                $job = new $d['job']($act, $limit, $i, $order);
                $batch->add($job);
            }

        }

        return redirect()->route('univ.mahasiswa');

    }

    public function daftar_mahasiswa_data(Request $request)
    {
        $searchValue = $request->input('search.value');

        $query = RiwayatPendidikan::with('prodi', 'biodata');

        if ($searchValue) {
            $query = $query->where('nim', 'like', '%' . $searchValue . '%')
                ->orWhere('nama_mahasiswa', 'like', '%' . $searchValue . '%');
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
            $columns = ['nama_mahasiswa', 'nim', 'nama_program_studi', 'id_periode_masuk'];

            // if ($columns[$orderColumn] == 'prodi') {
            //     $query = $query->join('program_studis as prodi', 'mata_kuliahs.id_prodi', '=', 'prodi.id')
            //         ->orderBy('prodi.nama_jenjang_pendidikan', $orderDirection)
            //         ->orderBy('prodi.nama_program_studi', $orderDirection)
            //         ->select('mata_kuliahs.*', 'prodi.nama_jenjang_pendidikan', 'prodi.nama_program_studi'); // Avoid column name conflicts
            // } else {
                $query = $query->orderBy($columns[$orderColumn], $orderDirection);
            // }
        }

        $data = $query->skip($offset)->take($limit)->get();

        $recordsTotal = RiwayatPendidikan::count();

        $response = [
            'draw' => $request->input('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ];

        return response()->json($response);
    }
}
