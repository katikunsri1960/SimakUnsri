<?php

namespace App\Http\Controllers\Universitas;

use App\Models\SemesterAktif;
use App\Models\Referensi\PeriodePerkuliahan;
use App\Http\Controllers\Controller;
use App\Models\ProgramStudi;
use App\Models\Semester;
use Illuminate\Http\Request;
use App\Services\Feeder\FeederAPI;

class PengaturanController extends Controller
{
    public function periode_perkuliahan(Request $request)
    {
        $semester = Semester::orderBy('id_semester', 'desc')->get();
        $prodi = ProgramStudi::all();

        $data = PeriodePerkuliahan::with('prodi', 'semester')
            ->orderBy('id_semester', 'desc')
            ->when($request->id_semester, function ($query, $id_semester) {
                return $query->whereIn('id_semester', $id_semester);
            })
            ->when($request->id_prodi, function ($query, $id_prodi) {
                return $query->whereIn('id_prodi', $id_prodi);
            })
            ->get();

        return view('universitas.pengaturan.periode-perkuliahan.index', [
            'data' => $data,
            'semester' => $semester,
            'prodi' => $prodi
        ]);
    }

    public function sync_periode_perkuliahan()
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '2048M');

        $act = 'GetDetailPeriodePerkuliahan';

        $prodi = ProgramStudi::pluck('id_prodi')->toArray();

        foreach ($prodi as $p) {

            $filter = "id_prodi = '".$p."'";
            $api = new FeederApi($act, 0, 0, '', $filter);

            $response = $api->runWS();

            $data = $response['data'];

            $data = array_map(function ($value) {
                $value['tanggal_awal_perkuliahan'] = empty($value['tanggal_awal_perkuliahan']) ? null : date('Y-m-d', strtotime($value['tanggal_awal_perkuliahan']));
                $value['tanggal_akhir_perkuliahan'] = empty($value['tanggal_akhir_perkuliahan']) ? null : date('Y-m-d', strtotime($value['tanggal_akhir_perkuliahan']));
                return $value;
            }, $data);

            PeriodePerkuliahan::upsert($data, ['id_prodi', 'id_semester']);
        }

        return redirect()->back()->with('success', 'Data berhasil di sinkronisasi');

    }

    public function semester_aktif()
    {
        $semester = Semester::orderBy('id_semester', 'desc')->get();
        $data = SemesterAktif::where('id', 1)->first();
        return view('universitas.pengaturan.semester-aktif', [
            'semester' => $semester,
            'data' => $data
        ]);
    }

    public function semester_aktif_store(Request $request)
    {
        $data = $request->validate([
            'id_semester' => 'required|exists:semesters,id_semester',
            'krs_mulai' => 'required',
            'krs_selesai' => 'required',
        ]);

        $data['id'] = 1;
        
        SemesterAktif::updateOrCreate(['id' => 1], $data);

        return redirect()->back()->with('success', 'Data berhasil disimpan');
    }
}
