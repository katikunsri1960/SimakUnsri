<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BiayaKuliahController extends Controller
{
    public function index()
    {
        return view('mahasiswa.biaya-kuliah.index');
    }
}
