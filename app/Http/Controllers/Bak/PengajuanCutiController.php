<?php

namespace App\Http\Controllers\Bak;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa\PengajuanCuti;
use App\Models\Semester;
use App\Models\SemesterAktif;
use Illuminate\Http\Request;

class PengajuanCutiController extends Controller
{
    public function index(Request $request)
    {
        $db = new PengajuanCuti;

        $request->validate([
            'semester_view' => 'nullable|exists:semesters,id_semester',
        ]);

        $data = $db->with(['riwayat', 'prodi']);

        $pilihan_semester = Semester::select('id_semester', 'nama_semester')->orderBy('id_semester', 'desc')->get();
        $semester_view = $request->semester_view ?? SemesterAktif::select('id_semester')->first()->id_semester;

        $data = $data->where('id_semester', $semester_view)->get();

        return view('bak.pengajuan-cuti.index',[
            'data' => $data,
            'pilihan_semester' => $pilihan_semester,
            'semester_view' => $semester_view,
        ]);
    }
}
