<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AkademikController extends Controller
{
    public function krs()
    {
        return view('mahasiswa.krs');
    }

    public function khs()
    {
        return view('mahasiswa.khs');
    }

    public function transkrip()
    {
        return view('mahasiswa.transkrip');
    }
}
