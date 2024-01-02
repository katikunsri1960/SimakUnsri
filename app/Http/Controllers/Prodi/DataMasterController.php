<?php

namespace App\Http\Controllers\Prodi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DataMasterController extends Controller
{
    public function dosen()
    {
        return view('prodi.data-master.dosen.index');
    }

    public function mahasiswa()
    {
        return view('prodi.data-master.mahasiswa.index');
    }

    public function matkul()
    {
        return view('prodi.data-master.mata-kuliah.index');
    }

    public function ruang_perkuliahan()
    {
        return view('prodi.data-master.ruang-perkuliahan.index');
    }

    public function ruang_perkuliahan_store(Request $request)
    {
        $data = $request->validate([
            'nama_ruang' => 'required',
            'lokasi' => 'required',
        ]);


    }

    public function kurikulum()
    {
        return view('prodi.data-master.kurikulum.index');
    }
}
