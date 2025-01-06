<?php

namespace App\Http\Controllers\Dosen\Penilaian;

use App\Http\Controllers\Controller;
use App\Models\Dosen\BiodataDosen;
use App\Models\Perkuliahan\KelasKuliah;
use App\Models\Semester;
use App\Models\SemesterAktif;
use Illuminate\Http\Request;

class RiwayatPenilaianController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'semester_view' => 'nullable|exists:semesters,id_semester'
        ]);

        $semester_view = $request->semester_view ?? null;
        $semester_aktif = SemesterAktif::select('id_semester')->first();

        $semester = Semester::select('id_semester', 'nama_semester')->orderBy('id_semester', 'desc')->get();

        $semester_pilih = $semester_view == null ? $semester_aktif->id_semester : $semester_view;

        $db = new BiodataDosen();
        $data = $db->riwayat_kelas(auth()->user()->fk_id, $semester_pilih);

        // dd($data);
        return view('dosen.penilaian.riwayat-penilaian.index', [
            'data' => $data,
            'semester' => $semester,
            'semester_pilih' => $semester_pilih,
            'semester_view' => $semester_view,
        ]);
    }

    public function detail(string $kelas)
    {
        $db = new KelasKuliah();
        $data = $db->detail_penilaian_perkuliahan($kelas);

        return view('dosen.penilaian.riwayat-penilaian.detail', [
            'data' => $data
        ]);
    }
}
