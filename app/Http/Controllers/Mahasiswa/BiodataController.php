<?php

namespace App\Http\Controllers\Mahasiswa;

use Illuminate\Http\Request;
use Illuminate\Support\Number;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Mahasiswa\BiodataMahasiswa;
use App\Models\Mahasiswa\RiwayatPendidikan;


class BiodataController extends Controller
{
    public function index()
    {
        $id_reg = auth()->user()->fk_id;

        $biodata = RiwayatPendidikan::with(['biodata', 'biodata.wilayah'])
                // ->addSelect(DB::raw('(SELECT nama_wilayah FROM wilayahs WHERE id_wilayah = id_induk_wilayah LIMIT 1) AS kab_kota'))
                // ->addSelect(DB::raw('(SELECT id_induk_wilayah FROM biodata_mahasiswas WHERE id_wilayah = id_wilayah AND id_level_wilayah = 3 LIMIT 1) AS id_induk_wilayah'))
                        // ->addSelect("body")
                        // ->addSelect(DB::raw('1 as number'))
                        
                // ->leftJoin('biodata_mahasiswas', 'biodata_mahasiswas.id_mahasiswa', '=', )
                        
                // ->where('id_registrasi_mahasiswa', '55f28aa8-168c-432e-b553-e64d150c423d')//PT Asal
                // ->where('id_registrasi_mahasiswa', '00013266-4d53-4314-a4bd-a3b0407a46b7')//Lulus
                
                ->where('id_registrasi_mahasiswa', $id_reg)//Default
                // ->first();
                
                ->limit(1)
                ->get();
                dd($biodata);
        

        return view('mahasiswa.biodata.index', compact('biodata'));
    }
}
