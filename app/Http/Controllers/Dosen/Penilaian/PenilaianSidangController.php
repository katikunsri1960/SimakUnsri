<?php

namespace App\Http\Controllers\Dosen\Penilaian;

use App\Http\Controllers\Controller;
use App\Models\Perkuliahan\AktivitasMahasiswa;
use App\Models\Perkuliahan\UjiMahasiswa;
use Illuminate\Http\Request;

class PenilaianSidangController extends Controller
{
    public function penilaian_sidang()
    {
        $db = new AktivitasMahasiswa;
        $data = $db->uji_dosen(auth()->user()->fk_id);
        // dd($data);
        return view('dosen.penilaian.penilaian-sidang.index', [
            'data' => $data
        ]);
    }
}
