<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AktivitasDosenController extends Controller
{
    public function aktivitas_dosen()
    {
        return view('dosen.aktivitas-dosen');
    }

    public function penelitian_dosen()
    {
        return view('dosen.aktivitas.penelitian-dosen');
    }

    public function publikasi_dosen()
    {
        return view('dosen.aktivitas.publikasi-dosen');
    }

    public function pengabdian_dosen()
    {
        return view('dosen.aktivitas.pengabdian-dosen');
    }
}
