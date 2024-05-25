<?php

namespace App\Http\Controllers\Dosen\Penilaian;

use App\Http\Controllers\Controller;
use App\Models\Dosen\BiodataDosen;
use App\Models\Perkuliahan\KelasKuliah;
use App\Models\SemesterAktif;
use App\Exports\ExportDPNA;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PenilaianPerkuliahanController extends Controller
{
    public function penilaian_perkuliahan()
    {
        $db = new BiodataDosen;

        $semester_aktif = SemesterAktif::first();
        $data = $db->dosen_pengajar_kelas(auth()->user()->fk_id);

        //Check batas pengisian nilai
        $hari_proses = Carbon::now();
        $batas_nilai = Carbon::createFromFormat('Y-m-d', $semester_aktif->batas_isi_nilai);
        $interval = $hari_proses->diffInDays($batas_nilai);

        return view('dosen.penilaian.penilaian-perkuliahan.index', [
            'data' => $data, 'semester_aktif' => $semester_aktif, 'batas_pengisian' => $interval
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
        $data_kelas = KelasKuliah::where('id_kelas_kuliah', $kelas)->get();
        return Excel::download(new ExportDPNA($kelas), 'DPNA_'.$data_kelas[0]['nama_program_studi'].'_'.$data_kelas[0]['kode_mata_kuliah'].'_'.$data_kelas[0]['nama_kelas_kuliah'].'.xlsx');
    }
}
