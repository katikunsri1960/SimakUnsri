<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KalenderAkademikController extends Controller
{
    public function kalender_akademik()
    {
        return view('dosen.kalender-akademik');
    }
}
