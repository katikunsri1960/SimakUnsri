<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AktivitasDosenController extends Controller
{
    public function aktivitas_dosen()
    {
        return view('dosen.aktivitasdosen');
    }
}
