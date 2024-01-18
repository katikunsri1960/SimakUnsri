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

        $prodi = ProgramStudi::pluck('id_prodi')->toArray();
        $semester = Semester::pluck('id_semester')->toArray();

        // dd($prodi, $semester);

        $batch = Bus::batch([])->name('Kelas Kuliah')->dispatch();

        $act = 'GetDetailKelasKuliah';
        $limit = '';
        $offset = 0;
        $order = '';

        foreach ($prodi as $p) {
            foreach ($semester as $s ) {
                $filter = "id_prodi = '$p' AND id_semester = '$s'";
                // dd($filter);
                $batch->add(new GetKelasJob($act, $limit, $offset, $order, $filter));
            }
        }

    }
}
