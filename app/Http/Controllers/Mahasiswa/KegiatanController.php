<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;

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
