<?php

namespace App\Http\Controllers\Mahasiswa\Akademik;

use App\Http\Controllers\Controller;
use App\Models\Connection\CourseUsept;
use App\Models\Connection\Usept;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Perkuliahan\ListKurikulum;

class NilaiUseptController extends Controller
{
    public function index()
    {
        $id_mahasiswa = auth()->user()->fk_id;
        $data_mahasiswa = RiwayatPendidikan::with('biodata')->where('id_registrasi_mahasiswa', $id_mahasiswa)->first();
        $nilai_usept_prodi = ListKurikulum::where('id_kurikulum', $data_mahasiswa->id_kurikulum)->first();
        $nilai_usept_mhs = Usept::whereIn('nim', [$data_mahasiswa->nim, $data_mahasiswa->biodata->nik])->get();
        $db_course_usept = new CourseUsept;
        $nilai_course = $db_course_usept->whereIn('nim', [$data_mahasiswa->nim, $data_mahasiswa->biodata->nik])->get();

        // dd($nilai_hasil_course);
        return view('mahasiswa.perkuliahan.nilai-usept.index', ['data' => $nilai_usept_mhs, 'usept_prodi' => $nilai_usept_prodi, 'course_data' => $nilai_course, 'mahasiswa' => $data_mahasiswa]);
    }

    public function devop()
    {
        return view('mahasiswa.perkuliahan.nilai-usept.devop');
    }
}
