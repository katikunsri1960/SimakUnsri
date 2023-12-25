<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BiodataDosenController extends Controller
{
    public function biodata_dosen()
    {
        return view('dosen.biodatadosen');
    }
}
