<?php

namespace App\Http\Controllers\Mahasiswa\Bimbingan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BimbinganController extends Controller
{
    public function bimbingan_tugas_akhir(Request $request)
    {
        return view('mahasiswa.bimbingan.tugas-akhir.index', [
            // 'data' => $data,
            // 'semester' => $semester,
            // 'id_semester' => $id_semester,
        ]);
    }
}
