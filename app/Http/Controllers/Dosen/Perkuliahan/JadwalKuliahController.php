<?php

namespace App\Http\Controllers\Dosen\Perkuliahan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JadwalKuliahController extends Controller
{
    public function jadwal_kuliah()
    {
        return view('dosen.perkuliahan.jadwal-kuliah');
    }
}
