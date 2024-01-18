<?php

namespace App\Http\Controllers\Prodi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\RuangPerkuliahan;

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
        $prodi_id = auth()->user()->fk_id;
        $data = $request->validate([
            'nama_ruang' => 'required',
            'lokasi' => [
                'required',
                Rule::unique('ruang_perkuliahans')->where(function ($query) use($request,$prodi_id) {
                    return $query->where('nama_ruang', $request->nama_ruang)
                    ->where('lokasi', $request->lokasi)
                    ->where('id_prodi', $prodi_id);
                }),
            ],
        ]);

        RuangPerkuliahan::create(['nama_ruang'=> $request->nama_ruang, 'lokasi'=> $request->lokasi, 'id_prodi'=> $prodi_id]);

        return redirect()->back()->with('success', 'Data Berhasil di Tambahkan');
    }

    public function kurikulum()
    {
        return view('prodi.data-master.kurikulum.index');
    }
}
