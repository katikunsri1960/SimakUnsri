<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Models\Wilayah;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Mahasiswa\BiodataMahasiswa;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Perkuliahan\MatkulKurikulum;
use App\Models\Semester;

class KrsController extends Controller
{
    public function krs()
    {

        $id_reg = auth()->user()->fk_id;
        $semester_aktif = Semester::where('id_semester', 20231)->get();

        $daftar_mk = MatkulKurikulum::where('semester', $semester_aktif->id_semester)->first();
        // dd($semester_aktif);
        

                
        return view('mahasiswa.krs.index', compact('semester_aktif'));
    }
}
