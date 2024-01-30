<?php

namespace App\Http\Controllers\Universitas;

use App\Models\Perkuliahan\MataKuliah;
use App\Http\Controllers\Controller;
use App\Models\Perkuliahan\KelasKuliah;
use App\Services\Feeder\FeederAPI;
use App\Models\ProgramStudi;
use App\Models\Referensi\JenisAktivitasMahasiswa;
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
                $batch->add(new $job($act, $limit, $offset, $order, $filter));
            }
        }

        return $batch;
    }

    private function sync2($act, $limit, $offset, $order, $job, $name, $model, $primary)
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
                $batch->add(new $job($act, $limit, $offset, $order, $filter, $model, $primary));
            }
        }

        return $batch;
    }



    public function sync_kelas_kuliah()
    {
        if (ProgramStudi::count() == 0 || Semester::count() == 0 || MataKuliah::count() == 0) {
            return redirect()->back()->with('error', 'Data Program Studi, Semester atau MK Kosong, Harap Sinkronkan Terlebih dahulu data Referensi!');
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
        $count = $this->count_value('GetCountDosenPengajarKelasKuliah');
        $limit = '';
        $offset = '';
        $order = '';

        $job = \App\Jobs\Perkuliahan\PengajarKelasJob::class;
        $name = 'pengajar-kelas-kuliah';

        $batch = $this->sync($act, $limit, $offset, $order, $job, $name);

        return redirect()->back()->with('success', 'Sinkronisasi Kelas Kuliah Berhasil!');
    }

    public function sync_peserta_kelas()
    {
        if (ProgramStudi::count() == 0 || KelasKuliah::count() == 0) {
            return redirect()->back()->with('error', 'Data Program Studi atau Semester Kosong, Harap Sinkronkan Terlebih dahulu data Referensi!');
        }

        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '1G');

        $act = 'GetPesertaKelasKuliah';
        $limit = '';
        $offset = '';
        $order = '';

        $batch = Bus::batch([])->name('peserta-kelas-kuliah')->dispatch();

        $kelasKuliahIds = KelasKuliah::select('id_kelas_kuliah')->get()->pluck('id_kelas_kuliah')->toArray();

        $chunks = array_chunk($kelasKuliahIds, 8);

        foreach ($chunks as $chunk) {
            $filter = "id_kelas_kuliah IN ('" . implode("','", $chunk) . "')";
            // dd($filter);
            $batch->add(new \App\Jobs\Perkuliahan\PesertaKelasJob($act, $limit, $offset, $order, $filter));
        }

        return redirect()->back()->with('success', 'Sinkronisasi Peserta Kelas Kuliah Berhasil!');
    }

    public function kelas_data(Request $request)
    {
        $searchValue = $request->input('search.value');

        $query = KelasKuliah::with('dosen_pengajar', 'prodi', 'semester', 'dosen_pengajar.dosen');

        if ($searchValue) {
            $query = $query->where('kode_mata_kuliah', 'like', '%' . $searchValue . '%')
                ->orWhere('nama_kelas_kuliah', 'like', '%' . $searchValue . '%')
                ->orWhere('nama_mata_kuliah', 'like', '%' . $searchValue . '%');
        }

        if ($request->has('prodi') && !empty($request->prodi)) {
            $filter = $request->prodi;
            $query->whereIn('id_prodi', $filter);
        }

        $recordsFiltered = $query->count();

        $limit = $request->input('length');
        $offset = $request->input('start');

        // Define the column names that correspond to the DataTables column indices
        if ($request->has('order')) {
            $orderColumn = $request->input('order.0.column');
            $orderDirection = $request->input('order.0.dir');

            // Define the column names that correspond to the DataTables column indices
            $columns = ['nama_semester', 'kode_mata_kuliah', 'nama_mata_kuliah'];

            // if ($columns[$orderColumn] == 'prodi') {
            //     $query = $query->join('program_studis as prodi', 'mata_kuliahs.id_prodi', '=', 'prodi.id')
            //         ->orderBy('prodi.nama_jenjang_pendidikan', $orderDirection)
            //         ->orderBy('prodi.nama_program_studi', $orderDirection)
            //         ->select('mata_kuliahs.*', 'prodi.nama_jenjang_pendidikan', 'prodi.nama_program_studi'); // Avoid column name conflicts
            // } else {
                $query = $query->orderBy($columns[$orderColumn], $orderDirection);
            // }
        }

        $data = $query->skip($offset)->take($limit)->get()->map(function ($kelasKuliah) {
            $kelasKuliahArray = $kelasKuliah->toArray(); // Convert the KelasKuliah to an array
            $kelasKuliahArray['nama_dosen'] = $kelasKuliah->dosen_pengajar->map(function ($dosenPengajar) {
                return $dosenPengajar->dosen->nama_dosen;
            })->implode(', ');
            return $kelasKuliahArray;
        });

        $recordsTotal = KelasKuliah::count();

        $response = [
            'draw' => $request->input('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ];

        return response()->json($response);
    }

    public function aktivitas_kuliah()
    {
        $prodi = ProgramStudi::select('nama_program_studi', 'id_prodi')->get();
        return view('universitas.perkuliahan.aktivitas-kuliah', compact('prodi'));
    }

    public function sync_aktivitas_kuliah()
    {
        if (ProgramStudi::count() == 0 || Semester::count() == 0) {
            return redirect()->back()->with('error', 'Data Program Studi atau Semester Kosong, Harap Sinkronkan Terlebih dahulu data Referensi!');
        }

        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '1G');

        $act = 'GetListPerkuliahanMahasiswa';
        $limit = '';
        $offset = '';
        $order = '';

        $job = \App\Jobs\Perkuliahan\PerkuliahanMahasiswaJob::class;
        $name = 'aktivitas-kuliah-mahasiswa';

        $batch = $this->sync($act, $limit, $offset, $order, $job, $name);

        return redirect()->back()->with('success', 'Sinkronisasi Kelas Kuliah Berhasil!');
    }

    public function aktivitas_kuliah_data(Request $request)
    {

    }

    public function aktivitas_mahasiswa()
    {
        return view('universitas.perkuliahan.aktivitas-mahasiswa');
    }

    public function aktivitas_mahasiswa_data(Request $request)
    {

    }

    public function sync_aktivitas_mahasiswa()
    {
        if (ProgramStudi::count() == 0 || Semester::count() == 0 || JenisAktivitasMahasiswa::count() == 0){
            return redirect()->back()->with('error', 'Harap Sinkronkan Terlebih dahulu data Referensi!');
        }

        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '1G');

        $act = 'GetListAktivitasMahasiswa';
        $limit = '';
        $offset = '';
        $order = '';

        $job = \App\Jobs\SyncJob::class;
        $name = 'aktivitas-mahasiswa';
        $model = \App\Models\Perkuliahan\AktivitasMahasiswa::class;
        $primary = 'id_aktivitas';

        $batch = $this->sync2($act, $limit, $offset, $order, $job, $name, $model,$primary);

        return redirect()->back()->with('success', 'Sinkronisasi Aktivitas Mahasiswa Berhasil!');
    }

    public function sync_anggota_aktivitas_mahasiswa()
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '1G');

        $data = [
            [
                'act' => 'GetListAnggotaAktivitasMahasiswa',
                'limit' => '',
                'offset' => '',
                'order' => '',
                'job' => \App\Jobs\SyncJob::class,
                'name' => 'anggota-aktivitas-mahasiswa',
                'model' => \App\Models\Perkuliahan\AnggotaAktivitasMahasiswa::class,
                'primary' => 'id_anggota',
                'reference' => \App\Models\Perkuliahan\AktivitasMahasiswa::class,
                'id' => 'id_aktivitas'
            ],
            [
                'act' => 'GetListBimbingMahasiswa',
                'limit' => '',
                'offset' => '',
                'order' => '',
                'job' => \App\Jobs\SyncJob::class,
                'name' => 'bimbing-mahasiswa',
                'model' => \App\Models\Perkuliahan\BimbingMahasiswa::class,
                'primary' => 'id_bimbing_mahasiswa',
                'reference' => \App\Models\Perkuliahan\AktivitasMahasiswa::class,
                'id' => 'id_aktivitas'
            ],
            [
                'act' => 'GetListUjiMahasiswa',
                'limit' => '',
                'offset' => '',
                'order' => '',
                'job' => \App\Jobs\SyncJob::class,
                'name' => 'uji-mahasiswa',
                'model' => \App\Models\Perkuliahan\UjiMahasiswa::class,
                'primary' => 'id_uji',
                'reference' => \App\Models\Perkuliahan\AktivitasMahasiswa::class,
                'id' => 'id_aktivitas'
            ]
        ];

        foreach ($data as $d) {
            $batch = $this->sync3($d['act'], $d['limit'], $d['offset'], $d['order'], $d['job'], $d['name'], $d['model'], $d['primary'], $d['reference'], $d['id']);
        }

        return redirect()->back()->with('success', 'Sinkronisasi Aktivitas Mahasiswa Berhasil!');
    }

    private function sync3($act, $limit, $offset, $order, $job, $name, $model, $primary, $reference, $id)
    {
        $reference = $reference::pluck($id)->toArray();
        $reference = array_chunk($reference, 40);

        $filter = array_map(function ($value) use ($id) {
            return "$id IN ('" . implode("','", $value) . "')";
        }, $reference);

        $batch = Bus::batch([])->name($name)->dispatch();

        foreach ($filter as $f) {
            $batch->add(new $job($act, $limit, $offset, $order, $f, $model, $primary));
        }

        return $batch;

    }

}
