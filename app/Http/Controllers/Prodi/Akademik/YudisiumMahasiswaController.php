<?php

namespace App\Http\Controllers\Prodi\Akademik;

use App\Http\Controllers\Controller;

class YudisiumMahasiswaController extends Controller
{
    public function yudisium_mahasiswa()
    {
        return view('prodi.data-akademik.yudisium-mahasiswa.index');
    }
}
