<?php

namespace App\Http\Controllers\Prodi\Akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class YudisiumMahasiswaController extends Controller
{
    public function yudisium_mahasiswa()
    {
        return view('prodi.data-akademik.yudisium-mahasiswa.index');
    }
}
