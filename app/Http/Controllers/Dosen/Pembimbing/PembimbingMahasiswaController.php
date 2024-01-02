<?php

namespace App\Http\Controllers\Dosen\Pembimbing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PembimbingMahasiswaController extends Controller
{
    public function bimbingan_akademik()
    {
        return view('dosen.pembimbing.bimbingan-akademik');
    }

    public function bimbingan_non_akademik()
    {
        return view('dosen.pembimbing.bimbingan-non-akademik');
    }

    public function bimbingan_tugas_akhir()
    {
        return view('dosen.pembimbing.bimbingan-tugas-akhir');
    }
}
