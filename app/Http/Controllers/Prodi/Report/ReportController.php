<?php

namespace App\Http\Controllers\Prodi\Report;

use App\Http\Controllers\Controller;
use App\Models\CutiManual;
use App\Models\Semester;
use App\Models\SemesterAktif;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function cuti_mahasiswa(Request $request)
    {
        $request->validate([
            'semester_view' => 'nullable|exists:semesters,id_semester',
        ]);


        $semester_aktif = SemesterAktif::first()->id_semester;
        $semester = Semester::select('id_semester', 'nama_semester')
                    ->where('id_semester', '<=', $semester_aktif)
                    ->whereNot('semester', 3)
                    ->orderBy('id_semester', 'desc')->get();

        $data = CutiManual::with(['riwayat'])->where('id_prodi', auth()->user()->fk_id);

        if ($request->semester_view) {
            $data = $data->where('id_semester', $request->semester_view);
        } else {
            $data = $data->where('id_semester', $semester_aktif);
        }

        $data = $data->get();

        return view('prodi.report.cuti-mahasiswa.index', [
            'data' => $data,
            'semester' => $semester,
            'semester_aktif' => $semester_aktif,
        ]);
    }
}
