<?php

namespace App\Http\Controllers\Prodi\Akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SidangMahasiswaController extends Controller
{
    public function sidang_mahasiswa()
    {
        return view('prodi.data-akademik.sidang-mahasiswa.index');
    }
}
