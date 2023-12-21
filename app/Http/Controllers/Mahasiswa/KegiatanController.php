<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KegiatanController extends Controller
{
    public function akademik()
    {
        return view('mahasiswa.kegiatan-akademik');
    }

    public function seminar()
    {
        return view('mahasiswa.kegiatan-seminar');
    }
}
