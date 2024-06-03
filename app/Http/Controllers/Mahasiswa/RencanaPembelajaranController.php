<?php

namespace App\Http\Controllers\Mahasiswa;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Perkuliahan\MataKuliah;
use App\Models\Perkuliahan\RencanaPembelajaran;

class RencanaPembelajaranController extends Controller
{
    public function getRPSData($id_matkul)
    {
        $rps = RencanaPembelajaran::where('id_matkul', $id_matkul)->orderBy('pertemuan', 'ASC')->get();
        return response()->json($rps);
    }

}
