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
            // ['act' => 'GetListRiwayatPendidikanMahasiswa', 'count' => 'GetCountRiwayatPendidikanMahasiswa', 'order' => 'id_registrasi_mahasiswa', 'job' => \App\Jobs\Mahasiswa\RiwayatPendidikanJob::class]
        ];

        $batch = Bus::batch([])->dispatch();

        foreach ($data as $d) {

            $count = $this->count_value($d['count']);

            $limit = 1000;
            $act = $d['act'];
            $order = $d['order'];

            for ($i=0; $i < $count; $i+=$limit) {
                $job = new $d['job']($act, $i, $limit, $order);
                $batch->add($job);
            }

        }

        return redirect()->route('univ.mahasiswa.index');

    }

    public function daftar_mahasiswa_data()
    {

    }
}
