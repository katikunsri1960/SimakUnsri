<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BiodataController extends Controller
{
    public function index()
    {
        $biodata = DB::table('pd_feeder_list_riwayat_pendidikan_mahasiswa')
            ->leftJoin('pd_feeder_biodata_mahasiswa','pd_feeder_biodata_mahasiswa.id_mahasiswa','=','pd_feeder_list_riwayat_pendidikan_mahasiswa.id_mahasiswa')
            
            ->select('*', 
                    DB::raw('CASE WHEN pd_feeder_list_riwayat_pendidikan_mahasiswa.jenis_kelamin = "P" THEN "Perempuan"
                                WHEN pd_feeder_list_riwayat_pendidikan_mahasiswa.jenis_kelamin = "L" THEN "Laki-Laki"
                                ELSE "Tidak Diisi"
                                END AS jenis_kelamin')
                    )
            // ->order('ni', 'desc')
            ->where('id_registrasi_mahasiswa', '010692d5-a378-4d17-a850-7c1fb4332e8b')
            ->first();
            
            // ->limit(1)
            // ->get()
            // ;
            // dd($biodata);

        return view('mahasiswa.biodata.index', compact('biodata'));
    }
}
