<?php

namespace App\Http\Controllers\Universitas;

use App\Models\SyncError;
use App\Models\Semester;
use App\Models\Mahasiswa\RiwayatPendidikan;
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
        $data = new FeederAPI($act,0,0, '', '');
        $response = $data->runWS();
        $count = $response['data'];

        return $count;
    }
    public function sync_mahasiswa()
    {
        $semester = Semester::orderBy('id_semester')
            ->pluck('id_semester');

        $data = [
            [
                'act'   => 'GetBiodataMahasiswa',
                'count' => 'GetCountBiodataMahasiswa',
                'order' => 'id_mahasiswa',
                'job'   => \App\Jobs\Mahasiswa\BiodataJob::class
            ],
            [
                'act'   => 'GetListMahasiswaLulusDO',
                'count' => 'GetCountMahasiswaLulusDO',
                'order' => 'id_registrasi_mahasiswa',
                'job'   => \App\Jobs\SyncJob::class
            ]
        ];

        $batch = Bus::batch([])->dispatch();

        foreach ($data as $d) {

            // Job tanpa filter semester
            if (!isset($d['filter'])) {

                $count = $this->count_value($d['count']);
                $limit = 1000;

                for ($i = 0; $i < $count; $i += $limit) {
                    $batch->add(
                        new $d['job'](
                            $d['act'], $i, $limit, $d['order'] ?? null, $d['filter'] ?? null
                        )
                    );
                }

                continue;
            }
        }

        // === Riwayat Pendidikan → per semester ===
        foreach ($semester as $idSemester) {

            $count = $this->count_value(
                'GetCountRiwayatPendidikanMahasiswa',
                "id_periode_masuk = '{$idSemester}'"
            );

            $limit = 1000;

            for ($i = 0; $i < $count; $i += $limit) {
                $batch->add(
                    new \App\Jobs\Mahasiswa\RiwayatPendidikanJob(
                        'GetListRiwayatPendidikanMahasiswa',
                        $i,
                        $limit,
                        'id_registrasi_mahasiswa',
                        "id_periode_masuk = '{$idSemester}'"
                    )
                );
            }
        }

        return redirect()->route('univ.mahasiswa');
    }


   /* public function sync_mahasiswa()
    {
        $data = [
            ['act' => 'GetBiodataMahasiswa', 'count' => 'GetCountBiodataMahasiswa', 'order' => 'id_mahasiswa', 'job' => \App\Jobs\Mahasiswa\BiodataJob::class],
            ['act' => 'GetListRiwayatPendidikanMahasiswa', 'count' => 'GetCountRiwayatPendidikanMahasiswa', 'order' => 'id_registrasi_mahasiswa', 'filter' => "id_periode_masuk = '20252'",'job' => \App\Jobs\Mahasiswa\RiwayatPendidikanJob::class],
            ['act' => 'GetListMahasiswaLulusDO', 'count' => 'GetCountMahasiswaLulusDO', 'order' => 'id_registrasi_mahasiswa', 'job' => \App\Jobs\SyncJob::class]
        ];

        $batch = Bus::batch([])->dispatch();

        foreach ($data as $d) {

            $count = $this->count_value($d['count']);

            $limit = 1000;
            $act = $d['act'];
            $order = $d['order'] ?? null;
            $filter = $d['filter'] ?? null;

            if ($d['act'] == 'GetListMahasiswaLulusDO') {

                for ($i=0; $i < $count; $i+=$limit) {
                    $job = new $d['job']($act, $i, $limit, $order, null, \App\Models\Mahasiswa\LulusDo::class, 'id_registrasi_mahasiswa');
                    $batch->add($job);
                }

            } else {
                for ($i=0; $i < $count; $i+=$limit) {
                    $job = new $d['job']($act, $i, $limit, $order, $filter);
                    $batch->add($job);
                }
            }


        }

        return redirect()->route('univ.mahasiswa');

    }*/

    public function daftar_mahasiswa_data(Request $request)
    {
        $searchValue = $request->input('search.value');

        $query = RiwayatPendidikan::with('prodi', 'biodata', 'lulus_do');

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

    public function sync_prestasi_mahasiswa()
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '1G');

        $data = [
            ['act' => 'GetListPrestasiMahasiswa', 'count' => 'GetCountPrestasiMahasiswa', 'order' => 'id_prestasi', 'model' => \App\Models\Mahasiswa\PrestasiMahasiswa::class],
            ['act' => 'GetNilaiTransferPendidikanMahasiswa', 'count' => 'GetCountNilaiTransferPendidikanMahasiswa', 'order' => 'id_transfer', 'model' => \App\Models\Perkuliahan\NilaiTransferPendidikan::class],
        ];


        foreach ($data as $d) {

            $count = $this->count_value($d['count']);

            $limit = 500;
            $act = $d['act'];
            $order = $d['order'];
            $filter = $d['filter'] ?? null;

            for ($i=0; $i < $count; $i+=$limit) {

                $api = new FeederAPI($act, $i, $limit, $order, $filter);
                $data = $api->runWS();

                try {

                    $d['model']::upsert($data['data'], $order);

                } catch (\Throwable $th) {
                    // return redirect()->back()->with('error', $th->getMessage());
                    foreach ($data['data'] as $a) {
                        try {
                            $d['model']::updateOrCreate([$order => $a[$order]], $a);
                        } catch (\Throwable $th) {
                            SyncError::create([
                                'model' => $act,
                                'message' => $th->getMessage()
                            ]);
                            continue;
                        }
                    }

                    continue;

                }

            }

        }

        return redirect()->back()->with('success', 'Data prestasi mahasiswa berhasil disinkronisasi');
    }
}
