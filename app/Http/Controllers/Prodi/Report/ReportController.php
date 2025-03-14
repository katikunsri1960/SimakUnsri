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
            'id_semester' => 'nullable|exists:semesters,id_semester',
        ]);

        $semester_aktif = SemesterAktif::first()->id_semester;
        $semester = Semester::select('id_semester', 'nama_semester')
                    ->where('id_semester', '<=', $semester_aktif)
                    ->whereNot('semester', 3)
                    ->orderBy('id_semester', 'desc')->get();

        $db = new CutiManual();

        $data = $db->with(['riwayat'])->filter($request)->where('id_prodi', auth()->user()->fk_id)->get();

        $status = CutiManual::STATUS;
        $total = $data->count();
        // count data per status dari $data
        $count = [];
        foreach ($status as $key => $value) {
            $count[$key]['status'] = $value['status'];
            $count[$key]['jumlah'] = $data->where('status', $key)->count();
            $count[$key]['persen'] = $total > 0 ? $count[$key]['jumlah'] / $total * 100 : 0;
            $count[$key]['class'] = $value['class'];
        }

        return view('prodi.report.cuti-mahasiswa.index', [
            'data' => $data,
            'semester' => $semester,
            'semester_aktif' => $semester_aktif,
            'count' => $count,
        ]);
    }
}
