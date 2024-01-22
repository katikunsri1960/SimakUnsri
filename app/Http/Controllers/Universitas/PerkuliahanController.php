<?php

namespace App\Http\Controllers\Universitas;

use App\Http\Controllers\Controller;
use App\Models\Perkuliahan\KelasKuliah;
use App\Services\Feeder\FeederAPI;
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

    private function count_value($act)
    {
        $data = new FeederAPI($act,0,0, '');
        $response = $data->runWS();
        $count = $response['data'];

        return $count;
    }

    private function sync($act, $limit, $offset, $order, $job, $name)
    {
        $prodi = ProgramStudi::pluck('id_prodi')->toArray();
        $semester = Semester::pluck('id_semester')->toArray();
        $semester = array_chunk($semester, 4);
        $semester = array_map(function ($value) {
            return "id_semester IN ('" . implode("','", $value) . "')";
        }, $semester);

        $batch = Bus::batch([])->name($name)->dispatch();

        foreach ($prodi as $p) {
            foreach ($semester as $s) {
                $filter = "id_prodi = '$p' AND $s";
                // dd($filter);
                $job = new $job($act, $limit, $offset, $order, $filter);
            }
        }

        return $batch;
    }

    public function sync_kelas_kuliah()
    {
        if (ProgramStudi::count() == 0 || Semester::count() == 0) {
            return redirect()->back()->with('error', 'Data Program Studi atau Semester Kosong, Harap Sinkronkan Terlebih dahulu data Referensi!');
        }

        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '1G');

        $act = 'GetDetailKelasKuliah';
        $limit = '';
        $offset = '';
        $order = '';

        $job = \App\Jobs\Perkuliahan\Kelas\GetKelasJob::class;
        $name = 'kelas-kuliah';

        $batch = $this->sync($act, $limit, $offset, $order, $job, $name);

        return redirect()->back()->with('success', 'Sinkronisasi Kelas Kuliah Berhasil!');

    }

    public function sync_pengajar_kelas()
    {
        if (ProgramStudi::count() == 0 || Semester::count() == 0) {
            return redirect()->back()->with('error', 'Data Program Studi atau Semester Kosong, Harap Sinkronkan Terlebih dahulu data Referensi!');
        }

        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '1G');

        $act = 'GetDosenPengajarKelasKuliah';
        $limit = '';
        $offset = '';
        $order = '';

        $job = \App\Jobs\Perkuliahan\Kelas\GetKelasJob::class;
        $name = 'pengajar-kelas-kuliah';

        $batch = $this->sync($act, $limit, $offset, $order, $job, $name);

        return redirect()->back()->with('success', 'Sinkronisasi Kelas Kuliah Berhasil!');
    }

    public function sync_peserta_kelas()
    {
        if (ProgramStudi::count() == 0 || KelasKuliah::count() == 0) {
            return redirect()->back()->with('error', 'Data Program Studi atau Semester Kosong, Harap Sinkronkan Terlebih dahulu data Referensi!');
        }



        return redirect()->back()->with('success', 'Sinkronisasi Peserta Kelas Kuliah Berhasil!');
    }
}
