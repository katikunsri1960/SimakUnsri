<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MengajarDosenController extends Controller
{
    public function mengajar_dosen()
    {
        return view('dosen.mengajardosen');
    }
}
