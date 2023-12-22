<?php

namespace App\Http\Controllers\Universitas;

use App\Models\ProgramStudi;
use App\Services\Feeder\FeederAPI;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReferensiController extends Controller
{
    public function prodi()
    {
        $data = ProgramStudi::all();

        return view('universitas.referensi.prodi', [
            'data' => $data,
        ]);
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
}
