<?php

namespace App\Http\Controllers\Universitas;

use App\Http\Controllers\Controller;
use App\Jobs\Perkuliahan\Kelas\GetKelasJob;
use App\Models\ProgramStudi;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;

class PerkuliahanController extends Controller
{
    public function kelas_kuliah()
    {
        return view('universitas.perkuliahan.kelas-kuliah');
    }

    public function sync_kelas_kuliah()
    {
        if (ProgramStudi::count() == 0 || Semester::count() == 0) {
            return redirect()->back()->with('error', 'Data Program Studi atau Semester Kosong, Harap Sinkronkan Terlebih dahulu data Referensi!');
        }

        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '1G');

        $prodi = ProgramStudi::pluck('id_prodi')->toArray();
        $semester = Semester::pluck('id_semester')->toArray();
        $semester = array_chunk($semester, 4);
        $semester = array_map(function ($value) {
            return "id_semester IN ('" . implode("','", $value) . "')";
        }, $semester);

        $batch = Bus::batch([])->name('Kelas Kuliah')->dispatch();

        $act = 'GetDetailKelasKuliah';
        $limit = '';
        $offset = '';
        $order = '';

        foreach ($prodi as $p) {
            foreach ($semester as $s) {
                $filter = "id_prodi = '$p' AND $s";
                // dd($filter);
                $batch->add(new GetKelasJob($act, $limit, $offset, $order, $filter));
            }
        }

        return redirect()->back()->with('success', 'Sinkronisasi Kelas Kuliah Berhasil!');

    }
}
