<?php

namespace App\Http\Controllers\Bak;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa\PengajuanCuti;
use Illuminate\Http\Request;

class PengajuanCutiController extends Controller
{
    public function index(Request $request)
    {
        $db = new PengajuanCuti;

        $data = $db->with(['riwayat', 'prodi']);


        if ($request->has('semester')) {
            $data = $data->where('semester', $request->semester);
        }

        $data = $data->get();

        return view('bak.pengajuan-cuti.index',[
            'data' => $data,
        ]);
    }
}
