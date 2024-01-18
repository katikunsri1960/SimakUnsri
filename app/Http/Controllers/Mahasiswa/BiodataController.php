<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Mahasiswa\RiwayatPendidikan;
use Illuminate\Support\Number;

class BiodataController extends Controller
{
    public function index()
    {
        $biodata = RiwayatPendidikan::with('biodata')
                ->where('id_registrasi_mahasiswa', '55f28aa8-168c-432e-b553-e64d150c423d')
                // ->where('id_registrasi_mahasiswa', '010692d5-a378-4d17-a850-7c1fb4332e8b')
                ->first();
                
                // ->limit(1)
                // ->get()
                // ;
                // dd($biodata);

        $pt_asal = RiwayatPendidikan::select( 'id_jenis_daftar', 'nama_jenis_daftar','nama_perguruan_tinggi_asal', 'nama_program_studi_asal')
                ->where('id_registrasi_mahasiswa', '55f28aa8-168c-432e-b553-e64d150c423d')
                // ->where('id_registrasi_mahasiswa', '010692d5-a378-4d17-a850-7c1fb4332e8b')
                ->get();
                // dd($pt_asal);

        $riwayat_pendidikan = RiwayatPendidikan::select( 'id_jenis_daftar', 'nama_jenis_daftar','nama_perguruan_tinggi_asal', 'nama_program_studi_asal')
                ->where('id_registrasi_mahasiswa', '55f28aa8-168c-432e-b553-e64d150c423d')
                // ->where('id_registrasi_mahasiswa', '010692d5-a378-4d17-a850-7c1fb4332e8b')
                ->get();
                // dd($pt_asal);

        return view('mahasiswa.biodata.index', compact('biodata', 'pt_asal', 'riwayat_pendidikan'));
    }
}
