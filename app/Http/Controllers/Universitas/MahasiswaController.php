<?php

namespace App\Http\Controllers\Universitas;

use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Mahasiswa\BiodataMahasiswa;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function daftar_mahasiswa()
    {
        return view('universitas.mahasiswa.index');
    }

    public function daftar_mahasiswa_data()
    {
        
    }
}
