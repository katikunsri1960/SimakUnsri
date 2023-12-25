<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BahanTugasController extends Controller
{
    public function index()
    {
        return view('mahasiswa.bahan-tugas');
    }
}
