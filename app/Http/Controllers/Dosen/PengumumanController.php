<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;

class PengumumanController extends Controller
{
    public function pengumuman()
    {
        return view('dosen.pengumuman');
    }
}
