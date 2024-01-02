<?php

namespace App\Http\Controllers\Dosen\Penilaian;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PenilaianSidangController extends Controller
{
    public function penilaian_sidang()
    {
        return view('dosen.penilaian.penilaian-sidang');
    }
}
