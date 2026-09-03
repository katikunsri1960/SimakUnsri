<?php

namespace App\Http\Controllers\DPPTI;

use App\Models\Semester;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use App\Models\SemesterAktif;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Mahasiswa\PengajuanCuti;
use App\Models\Perkuliahan\AktivitasKuliahMahasiswa;

class PengajuanCutiController extends Controller
{
    public function index(Request $request)
    {
        $db = new PengajuanCuti;

        $request->validate([
            'semester_view' => 'nullable|exists:semesters,id_semester',
        ]);

        $data = $db->with(['riwayat', 'prodi']);

        $semester_aktif = SemesterAktif::first()->id_semester;

        $pilihan_semester = Semester::select('id_semester', 'nama_semester')
                        ->whereBetween('id_semester', [20241, $semester_aktif])
                        ->whereNot('semester', 3)
                        ->orderBy('id_semester', 'desc')->get();

        $semester_view = $request->semester_view ?? $semester_aktif;

        $prodi = ProgramStudi::all();

        $data = $data->where('id_semester', $semester_view)
                ->get();

        return view('dppti.pengajuan-cuti.index',[
            'data' => $data,
            'pilihan_semester' => $pilihan_semester,
            'semester_view' => $semester_view,
        ]);
    }

}
