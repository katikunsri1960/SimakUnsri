<?php

namespace App\Http\Controllers\Mahasiswa\Akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NilaiUseptController extends Controller
{
    public function index()
    {
        return view('mahasiswa.perkuliahan.nilai-usept.index');
    }

    public function devop()
    {
        return view('mahasiswa.perkuliahan.nilai-usept.devop');
    }
}
