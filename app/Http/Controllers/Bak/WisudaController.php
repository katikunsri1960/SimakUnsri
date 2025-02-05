<?php

namespace App\Http\Controllers\Bak;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WisudaController extends Controller
{
    public function peserta(Request $request)
    {
        return view('bak.wisuda.peserta.index');
    }

    public function registrasi_ijazah(Request $request)
    {
        return view('bak.wisuda.registrasi-ijazah.index');
    }

    public function ijazah(Request $request)
    {
        return view('bak.wisuda.ijazah.index');
    }

    public function transkrip(Request $request)
    {
        return view('bak.wisuda.transkrip.index');
    }

    public function album(Request $request)
    {
        return view('bak.wisuda.album.index');
    }

    public function usept(Request $request)
    {
        return view('bak.wisuda.usept.index');
    }
}
