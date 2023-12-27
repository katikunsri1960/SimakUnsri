<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RiwayatPendidikanDosenController extends Controller
{
    public function riwayat_pendidikan_dosen()
    {
        return view('dosen.riwayatpendidikandosen');
    }
}
