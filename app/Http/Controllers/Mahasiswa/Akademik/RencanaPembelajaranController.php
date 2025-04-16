<?php

namespace App\Http\Controllers\Mahasiswa\Akademik;

use App\Http\Controllers\Controller;
use App\Models\Perkuliahan\RencanaPembelajaran;

class RencanaPembelajaranController extends Controller
{
    public function getRPSData($id_matkul)
    {
        $rps = RencanaPembelajaran::where('id_matkul', $id_matkul)->orderBy('pertemuan', 'ASC')->where('approved', '1')->get();

        return response()->json($rps);
    }
}
