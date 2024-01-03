<?php

namespace App\Http\Controllers\Dosen\Penilaian;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PresentasePenilaianController extends Controller
{
    public function presentase_penilaian_perkuliahan()
    {
        return view('dosen.penilaian.presentase.presentase-penilaian-perkuliahan');
    }
}
