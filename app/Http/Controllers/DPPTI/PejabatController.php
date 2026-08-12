<?php

namespace App\Http\Controllers\DPPTI;

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

        return view('dppti.data-master.pejabat.fakultas', [
            'data' => $data
        ]);
    }

    public function pejabat_universitas()
    {

        $jabatan = PejabatUniversitasJabatan::with('pejabat')->get();

        return view('dppti.data-master.pejabat.universitas', [
            'jabatan' => $jabatan
        ]);
    }

}
