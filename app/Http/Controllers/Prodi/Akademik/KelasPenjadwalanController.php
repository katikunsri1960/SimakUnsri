<?php

namespace App\Http\Controllers\Prodi\Akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KelasPenjadwalanController extends Controller
{
    public function kelas_penjadwalan()
    {
        return view('prodi.data-akademik.kelas-penjadwalan.index');
    }
}
