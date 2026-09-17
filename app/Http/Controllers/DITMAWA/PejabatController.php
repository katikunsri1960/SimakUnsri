<?php

namespace App\Http\Controllers\DITMAWA;

use App\Http\Controllers\Controller;
use App\Models\PejabatFakultas;
use App\Models\Referensi\PejabatUniversitas;
use App\Models\Referensi\PejabatUniversitasJabatan;
use Illuminate\Http\Request;

class PejabatController extends Controller
{
    public function pejabat_fakultas(Request $request)
    {
        $data = PejabatFakultas::all();

        return view('ditmawa.data-master.pejabat.fakultas', [
            'data' => $data
        ]);
    }

    public function pejabat_universitas()
    {

        $jabatan = PejabatUniversitasJabatan::with('pejabat')->get();

        return view('ditmawa.data-master.pejabat.universitas', [
            'jabatan' => $jabatan
        ]);
    }

}
