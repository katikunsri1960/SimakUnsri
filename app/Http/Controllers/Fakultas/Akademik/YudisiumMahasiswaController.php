<?php

namespace App\Http\Controllers\Fakultas\Akademik;

use App\Http\Controllers\Controller;

class YudisiumMahasiswaController extends Controller
{
    public function yudisium_mahasiswa()
    {
        return view('fakultas.data-akademik.yudisium-mahasiswa.index');
    }
}
