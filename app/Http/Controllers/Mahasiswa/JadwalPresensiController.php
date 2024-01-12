<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JadwalPresensiController extends Controller
{
    public function index()
    {
        return view('mahasiswa.jadwal-presensi.index');
    }
}
