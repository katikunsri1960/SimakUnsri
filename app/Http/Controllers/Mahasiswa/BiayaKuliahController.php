<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mahasiswa\RiwayatPendidikan;

class BiayaKuliahController extends Controller
{
    public function index()
    {
        $id_reg = auth()->user()->fk_id;
        $biaya_kuliah = RiwayatPendidikan::with('biodata')
        // ->where('id_registrasi_mahasiswa', '55f28aa8-168c-432e-b553-e64d150c423d')//PT Asal
        // ->where('id_registrasi_mahasiswa', '00013266-4d53-4314-a4bd-a3b0407a46b7')//Lulus
        
        ->where('id_registrasi_mahasiswa', $id_reg)//Default
        ->first();
        
        // ->limit(1)
        // ->get()
        // ;
        // dd($nim);

        return view('mahasiswa.biaya-kuliah.index', compact('biaya_kuliah'));
    }
}
