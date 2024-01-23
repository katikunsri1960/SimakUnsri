<?php

namespace App\Http\Controllers\Prodi\Akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Perkuliahan\KelasKuliah;

class KelasPenjadwalanController extends Controller
{
    public function kelas_penjadwalan()
    {
        $prodi_id = auth()->user()->fk_id;
        $data = KelasKuliah::where('id_prodi',$prodi_id)->get();
        // dd($data);
        return view('prodi.data-akademik.kelas-penjadwalan.index', ['data' => $data]);
    }
}
