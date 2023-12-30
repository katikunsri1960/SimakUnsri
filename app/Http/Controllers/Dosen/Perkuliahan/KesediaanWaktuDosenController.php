<?php

namespace App\Http\Controllers\Dosen\Perkuliahan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KesediaanWaktuDosenController extends Controller
{
    public function kesediaan_waktu_bimbingan()
    {
        return view('dosen.perkuliahan.kesediaan-waktu-bimbingan');
    }

    public function kesediaan_waktu_kuliah()
    {
        return view('dosen.perkuliahan.kesediaan-waktu-kuliah');
    }
}
