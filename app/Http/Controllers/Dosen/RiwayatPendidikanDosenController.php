<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;

class RiwayatPendidikanDosenController extends Controller
{
    public function riwayat_pendidikan_dosen()
    {
        return view('dosen.riwayat-pendidikan-dosen');
    }
}
