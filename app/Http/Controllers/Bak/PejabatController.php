<?php

namespace App\Http\Controllers\Bak;

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

    public function pejabat_universitas_store(Request $request)
    {
        $data = $request->validate([
            'jabatan_id' => 'required|exists:pejabat_universitas_jabatans,id',
            'nip' => 'required',
            'gelar_depan' => 'nullable',
            'nama' => 'required',
            'gelar_belakang' => 'nullable',
        ]);


        try {
            PejabatUniversitas::updateOrCreate(['jabatan_id' => $data['jabatan_id']], [
                'nip' => $data['nip'],
                'gelar_depan' => $data['gelar_depan'],
                'nama' => $data['nama'],
                'gelar_belakang' => $data['gelar_belakang'],
            ]);
        } catch (\Throwable $th) {
            return redirect()->route('bak.pejabat.universitas')->with('error', 'Data pejabat universitas gagal disimpan!');
        }
        // PejabatUniversitas::create($data);

        return redirect()->route('bak.pejabat.universitas')->with('success', 'Data pejabat universitas berhasil disimpan!');
    }
}
