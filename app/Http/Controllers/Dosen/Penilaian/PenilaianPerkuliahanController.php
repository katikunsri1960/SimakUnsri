<?php

namespace App\Http\Controllers\Dosen\Penilaian;

use App\Http\Controllers\Controller;
use App\Models\Dosen\BiodataDosen;
use App\Models\Perkuliahan\KelasKuliah;
use App\Models\SemesterAktif;
use App\Exports\ExportDPNA;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class PenilaianPerkuliahanController extends Controller
{
    public function penilaian_perkuliahan()
    {
        $db = new BiodataDosen;

        $data = $db->dosen_pengajar_kelas(auth()->user()->fk_id);

        return view('dosen.penilaian.penilaian-perkuliahan.index', [
            'data' => $data
        ]);
    }

    public function detail_penilaian_perkuliahan(string $kelas)
    {
        $db = new KelasKuliah;
        $data = $db->detail_penilaian_perkuliahan($kelas);

        return view('dosen.penilaian.penilaian-perkuliahan.detail', [
            'data' => $data
        ]);
    }

    public function download_dpna(string $kelas)
    {
        return Excel::download(new ExportDPNA($kelas), 'DPNA_"'.$kelas.'".xlsx');
    }
}
