<?php

namespace App\Http\Controllers\Dosen\Penilaian;

use App\Http\Controllers\Controller;
use App\Models\Dosen\BiodataDosen;
use App\Models\SemesterAktif;
use Illuminate\Http\Request;

class PenilaianPerkuliahanController extends Controller
{
    public function penilaian_perkuliahan()
    {
        $db = new BiodataDosen;

        $data = $db->dosen_pengajar_kelas(auth()->user()->fk_id);

        return view('dosen.penilaian.penilaian-perkuliahan', [
            'data' => $data
        ]);
    }
}
