<?php

namespace App\Http\Controllers\Universitas;

use App\Http\Controllers\Controller;
use App\Models\Dosen\BiodataDosen;
use App\Models\Dosen\PenugasanDosen;
use App\Models\ProgramStudi;
use App\Models\Semester;
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
        // dd($data);
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

    public function sync_penugasan_dosen()
    {

        if (ProgramStudi::count() == 0 || Semester::count() == 0) {
            return redirect()->back()->with('error', 'Data Program Studi atau Semester Kosong, Harap Sinkronkan Terlebih dahulu data Referensi!');
        }

        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '1G');

        $act = 'GetListPenugasanDosen';
        $limit = 1000;
        $offset = 0;
        $order = 'id_registrasi_dosen';

        $count = $this->count_value('GetCountPenugasanSemuaDosen');

        $batch = Bus::batch([])->dispatch();

        for($i=0; $i < $count; $i+=$limit) {
            $job = new \App\Jobs\Dosen\PenugasanDosenJob($act, $limit, $i, $order);
            $batch->add($job);
        }

        return redirect()->back()->with('success', 'Sinkronisasi Data Penugasan Dosen Berhasil!');
    }

    public function sync_dosen_all()
    {
        $data = [
            'act' => 'GetRiwayatPendidikanDosen',
            'limit' => '',
            'offset' => '',
            'order' => '',
            'job' => \App\Jobs\SyncJob::class,
            'name' => 'pendidikan-dosen',
            'model' => \App\Models\Dosen\RiwayatPendidikanDosen::class,
            'primary' => ['id_dosen', 'id_jenjang_pendidikan', 'id_bidang_studi'],
        ];

        $dosen = BiodataDosen::pluck('id_dosen')->toArray();
        $dosen = array_chunk($dosen, 8);
        $dosen = array_map(function ($value) {
            return "id_dosen IN ('" . implode("','", $value) . "')";
        }, $dosen);

        $batch = Bus::batch([])->name($data['name'])->dispatch();

        foreach ($dosen as $s) {
            $filter = "$s";
            // dd($filter);
            $batch->add(new $data['job']($data['act'], $data['limit'], $data['offset'], $data['order'], $filter, $data['model'], $data['primary']));
        }

        return redirect()->back()->with('success', 'Sinkronisasi Nilai Perkuliahan Berhasil!');
    }
}
