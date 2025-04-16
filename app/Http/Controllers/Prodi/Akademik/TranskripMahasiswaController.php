<?php

namespace App\Http\Controllers\Prodi\Akademik;

use App\Http\Controllers\Controller;

class TranskripMahasiswaController extends Controller
{
    public function transkrip_mahasiswa()
    {
        return view('prodi.data-akademik.transkrip-mahasiswa.index');
    }
}
