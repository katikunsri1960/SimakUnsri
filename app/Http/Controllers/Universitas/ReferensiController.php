<?php

namespace App\Http\Controllers\Universitas;

use App\Models\ProgramStudi;
use App\Models\Wilayah;
use App\Models\LevelWilayah;
use App\Models\Negara;
use App\Services\Feeder\FeederAPI;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReferensiController extends Controller
{
    public function prodi()
    {
        $data = ProgramStudi::all();

        return view('universitas.referensi.prodi', [
            'data' => $data,
        ]);
    }

    private function sync($act, $limit, $offset, $order)
    {
        $get = new FeederAPI($act, $offset, $limit, $order);

        $data = $get->runWS();

        return $data;
    }

    public function sync_prodi()
    {
        $act = 'GetProdi';
        $offset = 0;
        $limit = 0;
        $order = '';

        $prodi = new FeederAPI($act, $offset, $limit, $order);

        $prodi = $prodi->runWS();

        if (!empty($prodi['data'])) {
            foreach ($prodi['data'] as $p) {
                ProgramStudi::updateOrCreate(['id_prodi' => $p['id_prodi']], $p);
            }
        }

        return redirect()->route('univ.referensi.prodi');

    }

    public function sync_referensi()
    {
        $ref = [
            ['act' => 'GetLevelWilayah', 'model' => LevelWilayah::class],
            ['act' => 'GetWilayah', 'model' => Wilayah::class],
            ['act' => 'GetNegara', 'model' => Negara::class],
        ];

        foreach ($ref as $r) {
            $act = $r['act'];
            $offset = 0;
            $limit = 0;
            $order = '';

            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            $r['model']::truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            $data = $this->sync($act, $limit, $offset, $order);

            if (!empty($data['data'])) {
                $r['model']::insert($data['data']);
            }
        }

        return redirect()->route('univ.referensi.prodi');


    }
}
