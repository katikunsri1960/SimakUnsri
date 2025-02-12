<?php

namespace App\Http\Controllers\Fakultas\Akademik;

use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use App\Models\Connection\Usept;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Connection\CourseUsept;
use App\Models\Perkuliahan\ListKurikulum;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Perkuliahan\TranskripMahasiswa;
use App\Models\Perkuliahan\AktivitasKuliahMahasiswa;

class NilaiUSEPTController extends Controller
{
    public function index(Request $request)
    {
        return view('fakultas.data-akademik.nilai-usept.index');
    }

    public function data(Request $request)
    {
        
        $id_prodi_fak = ProgramStudi::where('fakultas_id', auth()->user()->fk_id)
                    ->pluck('id_prodi');

        $nim = $request->nim;

        $riwayat = RiwayatPendidikan::with(['pembimbing_akademik', 'prodi.jurusan', 'prodi.fakultas', 'biodata'])
                    ->where('nim', $nim)
                    ->whereIn('id_prodi', $id_prodi_fak)
                    ->first();

        if (!$riwayat) {
            return back()->with('error', 'Data Mahasiswa tidak ditemukan!');
        }
                    
        $nilai_usept_prodi = ListKurikulum::where('id_kurikulum', $riwayat->id_kurikulum)->first();

        $test_usept = Usept::whereIn('nim', [$riwayat->nim, $riwayat->biodata->nik])->get();

        $course_usept = CourseUsept::whereIn('nim', [$riwayat->nim, $riwayat->biodata->nik])->get();

        // dd($test_usept, $course_usept);

        if (!$test_usept) {
            return back()->with('error', 'Nilai USEPT Mahasiswa tidak ditemukan');
        }
        // dd($test_usept, $course_usept, $riwayat);
        return view('fakultas.data-akademik.nilai-usept.index', [
            'riwayat'=> $riwayat, 'test_usept'=> $test_usept, 'course_usept'=> $course_usept, 'nilai_usept_prodi'=> $nilai_usept_prodi]
        );
    }
}
