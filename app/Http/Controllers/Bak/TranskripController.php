<?php

namespace App\Http\Controllers\Bak;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Perkuliahan\AktivitasKuliahMahasiswa;
use App\Models\Perkuliahan\TranskripMahasiswa;
use Illuminate\Http\Request;

class TranskripController extends Controller
{
    public function index()
    {
        return view('bak.transkrip.index');
    }

    public function data(Request $request)
    {
        $request->validate([
            'nim' => 'required',
        ]);

        $riwayat = RiwayatPendidikan::with(['prodi.fakultas', 'prodi.jurusan', 'pembimbing_akademik'])->where('nim', $request->nim)->orderBy('id_periode_masuk', 'desc')->first();

        if(!$riwayat) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data Mahasiswa tidak ditemukan!!',
            ]);
        }

        $transkrip = TranskripMahasiswa::where('id_registrasi_mahasiswa', $riwayat->id_registrasi_mahasiswa)->get();

        $akm = AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa', $riwayat->id_registrasi_mahasiswa)
                ->orderBy('id_semester', 'desc')
                ->get();

        $data = [
            'status' => 'success',
            'data' => $transkrip,
            'akm' => $akm,
            'riwayat' => $riwayat,
        ];

        return response()->json($data);

    }
}
