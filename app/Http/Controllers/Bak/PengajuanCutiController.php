<?php

namespace App\Http\Controllers\Bak;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PengajuanCutiController extends Controller
{
    public function index(Request $request)
    {
        return view('bak.pengajuan-cuti.index',[

        ]);
    }
}
