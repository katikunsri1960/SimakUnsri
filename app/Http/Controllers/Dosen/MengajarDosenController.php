<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;

class MengajarDosenController extends Controller
{
    public function mengajar_dosen()
    {
        return view('dosen.mengajar-dosen');
    }
}
