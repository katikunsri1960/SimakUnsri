<?php

namespace App\Http\Controllers\Fakultas\Akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TranskripMahasiswaController extends Controller
{
    public function transkrip_mahasiswa()
    {
        return view('prodi.data-akademik.transkrip-mahasiswa.index');
    }
}
