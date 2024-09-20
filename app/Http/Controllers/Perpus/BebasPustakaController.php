<?php

namespace App\Http\Controllers\Perpus;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa\RiwayatPendidikan;
use Illuminate\Http\Request;

class BebasPustakaController extends Controller
{
    public function index()
    {
        return view('perpus.bebas-pustaka.index');
    }

    public function getData(Request $request)
    {
        $nim = $request->nim;

        $riwayat = RiwayatPendidikan::where('nim', $nim)->orderBy('id_periode_masuk', 'desc')->first();

        if ($riwayat) {
            return response()->json([
                'status' => 'success',
                'data' => $riwayat
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }
}
