<?php

namespace App\Http\Controllers\Prodi\Report;

use App\Http\Controllers\Controller;
use App\Models\CutiManual;
use App\Models\PenundaanBayar;
use App\Models\Semester;
use App\Models\SemesterAktif;
use App\Models\Perkuliahan\AktivitasMahasiswa;
use App\Models\Mahasiswa\PrestasiMahasiswa;
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

    public function tunda_bayar(Request $request)
    {
        $semester_aktif = SemesterAktif::first()->id_semester;
        $semester = Semester::select('id_semester', 'nama_semester')
                    ->where('id_semester', '<=', $semester_aktif)
                    ->whereNot('semester', 3)
                    ->orderBy('id_semester', 'desc')->get();

        $db = new PenundaanBayar();

        $data = $db->with(['riwayat'])->filter($request)
                ->whereHas('riwayat', function($q) {
                    $q->where('id_prodi', auth()->user()->fk_id);
                })->get();

        $status = PenundaanBayar::STATUS;
        $total = $data->count();
        // count data per status dari $data
        $count = [];
        foreach ($status as $key => $value) {
            $count[$key]['status'] = $value['status'];
            $count[$key]['jumlah'] = $data->where('status', $key)->count();
            $count[$key]['persen'] = $total > 0 ? $count[$key]['jumlah'] / $total * 100 : 0;
            $count[$key]['class'] = $value['class'];
        }

        return view('prodi.report.tunda-bayar.index', [
            'semester' => $semester,
            'semester_aktif' => $semester_aktif,
            'data' => $data,
            'count' => $count,
        ]);
    }

    public function aktivitas_penelitian()
    {
        $id_prodi = auth()->user()->fk_id;
        $data = AktivitasMahasiswa::with('anggota_aktivitas_personal')->where('id_prodi', $id_prodi)->whereIn('id_jenis_aktivitas', ['15','23'])->get();

        return view('prodi.report.penelitian-mahasiswa.index', ['data' => $data]);
    }

    public function aktivitas_lomba()
    {
        $id_prodi = auth()->user()->fk_id;
        $data = PrestasiMahasiswa::with([
            'biodata_mahasiswa',
            'biodata_mahasiswa.riwayat_pendidikan' => function ($query) use ($id_prodi) {
                $query->where('id_prodi', $id_prodi);
            }
        ])
        ->whereHas('biodata_mahasiswa.riwayat_pendidikan', function ($query) use ($id_prodi) {
            $query->where('id_prodi', $id_prodi);
        })
        ->get();    

        // dd($data);
        return view('prodi.report.prestasi-mahasiswa.index', ['data' => $data]);
    }
}
