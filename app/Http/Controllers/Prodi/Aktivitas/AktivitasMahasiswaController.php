<?php

namespace App\Http\Controllers\Prodi\Aktivitas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AktivitasMahasiswaController extends Controller
{
    public function aktivitas_penelitian()
    {
        return view('prodi.data-aktivitas.aktivitas-penelitian.index');
    }

    public function aktivitas_lomba()
    {
        return view('prodi.data-aktivitas.aktivitas-lomba.index');
    }

    public function aktivitas_organisasi()
    {
        return view('prodi.data-aktivitas.aktivitas-organisasi.index');
    }
}
