<?php

namespace App\Http\Controllers\Dosen\Bantuan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GantiPasswordController extends Controller
{
    public function ganti_password()
    {
        return view('dosen.bantuan.ganti-password');
    }
}
