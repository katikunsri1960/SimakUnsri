<?php

namespace App\Http\Controllers\Dosen\Perkuliahan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JadwalBimbinganController extends Controller
{
    public function jadwal_bimbingan()
    {
        return view('dosen.perkuliahan.jadwal-bimbingan');
    }
}
