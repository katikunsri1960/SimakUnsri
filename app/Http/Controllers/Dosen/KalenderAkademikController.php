<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;

class KalenderAkademikController extends Controller
{
    public function kalender_akademik()
    {
        return view('dosen.kalender-akademik');
    }
}
