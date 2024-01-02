<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AkademikController extends Controller
{
    public function krs()
    {
        return view('mahasiswa.krs.index');
    }

    public function create_krs()
    {
        return view('mahasiswa.krs.create');
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
