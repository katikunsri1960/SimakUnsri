<?php

namespace App\Http\Controllers\Prodi\Akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KHSController extends Controller
{
    public function khs()
    {
        return view('prodi.data-akademik.khs.index');
    }
}
