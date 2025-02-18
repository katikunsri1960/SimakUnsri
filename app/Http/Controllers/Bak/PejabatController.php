<?php

namespace App\Http\Controllers\Bak;

use App\Http\Controllers\Controller;
use App\Models\PejabatFakultas;
use App\Models\Referensi\PejabatUniversitasJabatan;
use Illuminate\Http\Request;

class PejabatController extends Controller
{
    public function pejabat_fakultas(Request $request)
    {
        $data = PejabatFakultas::all();

        return view('bak.pejabat.fakultas.index', [
            'data' => $data
        ]);
    }

    public function pejabat_universitas()
    {

        $jabatan = PejabatUniversitasJabatan::with('pejabat')->get();

        return view('bak.pejabat.universitas.index', [
            'jabatan' => $jabatan
        ]);
    }
}
