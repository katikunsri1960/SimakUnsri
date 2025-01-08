<?php

namespace App\Http\Controllers\Universitas;

use App\Models\Perkuliahan\MataKuliah;
use App\Http\Controllers\Controller;
use App\Models\Perkuliahan\AktivitasKuliahMahasiswa;
use App\Models\Perkuliahan\AktivitasMahasiswa;
use App\Models\Perkuliahan\KelasKuliah;
use App\Models\Perkuliahan\KomponenEvaluasiKelas;
use App\Models\Perkuliahan\TranskripMahasiswa;
use App\Services\Feeder\FeederAPI;
use App\Models\ProgramStudi;
use App\Models\Referensi\JenisAktivitasMahasiswa;
use App\Models\Semester;
use App\Models\SemesterAktif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;

class PerkuliahanController extends Controller
{
    public function kelas_kuliah()
    {
        $semester = Semester::select('id_semester', 'nama_semester')->orderBy('id_semester', 'desc')->get();
        $prodi = ProgramStudi::select('id_prodi', 'kode_program_studi', 'nama_program_studi', 'nama_jenjang_pendidikan')->get();
        return view('universitas.perkuliahan.kelas-kuliah.index', ['prodi' => $prodi, 'semester' => $semester]);
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
        $semester = array_chunk($semester, 6);
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

        $job = \App\Jobs\SyncJob::class;
        $name = 'kelas-kuliah';
        $model = \App\Models\Perkuliahan\KelasKuliah::class;
        $primary = 'id_kelas_kuliah';

        $batch = $this->sync2($act, $limit, $offset, $order, $job, $name, $model,$primary);

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
        ini_set('memory_limit', '2G');

        $act = 'GetPesertaKelasKuliah';
        $limit = '';
        $offset = '';
        $order = '';

        $batch = Bus::batch([])->name('peserta-kelas-kuliah')->dispatch();

        $kelasKuliahIds = MataKuliah::select('id_matkul')->get()->pluck('id_matkul')->toArray();

        $chunks = array_chunk($kelasKuliahIds, 20);

        foreach ($chunks as $chunk) {
            $filter = "id_matkul IN ('" . implode("','", $chunk) . "')";
            // dd($filter);
            $batch->add(new \App\Jobs\Perkuliahan\PesertaKelasJob($act, $limit, $offset, $order, $filter));
        }

        return redirect()->back()->with('success', 'Sinkronisasi Peserta Kelas Kuliah Berhasil!');
    }

    public function kelas_data(Request $request)
    {
        $searchValue = $request->input('search.value');

        $query = KelasKuliah::with('dosen_pengajar', 'prodi', 'semester', 'dosen_pengajar.dosen', 'matkul')
                            ->withCount('peserta_kelas');

        if ($request->filled('id_prodi')) {
            $query->whereIn('id_prodi', $request->id_prodi);
        }

        if ($request->filled('id_semester')) {
            $query->whereIn('id_semester', $request->id_semester);
        }

        if ($searchValue) {
            $query = $query->where('kode_mata_kuliah', 'like', '%' . $searchValue . '%')
                ->orWhere('nama_kelas_kuliah', 'like', '%' . $searchValue . '%')
                ->orWhere('nama_mata_kuliah', 'like', '%' . $searchValue . '%');
        }

        $recordsFiltered = $query->count();

        $limit = $request->input('length');
        $offset = $request->input('start');

        // Define the column names that correspond to the DataTables column indices
        if ($request->has('order')) {
            $orderColumn = $request->input('order.0.column');
            $orderDirection = $request->input('order.0.dir');

            // Define the column names that correspond to the DataTables column indices
            $columns = ['nama_semester', 'kode_mata_kuliah', 'nama_mata_kuliah', 'nama_kelas_kuliah'];

            $query = $query->orderBy($columns[$orderColumn], $orderDirection);

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
        $prodi = ProgramStudi::select('nama_program_studi', 'id_prodi', 'kode_program_studi', 'nama_jenjang_pendidikan')->orderBy('kode_program_studi')->get();
        $angkatan = AktivitasKuliahMahasiswa::select('angkatan')->distinct()->orderBy('angkatan', 'desc')->get();
        $semester = Semester::select('nama_semester', 'id_semester')->orderBy('id_semester', 'desc')->get();
        $status_mahasiswa = AktivitasKuliahMahasiswa::select('id_status_mahasiswa', 'nama_status_mahasiswa')->distinct()->orderBy('id_status_mahasiswa')->get();
        return view('universitas.perkuliahan.aktivitas-kuliah.index', [
            'prodi' => $prodi,
            'angkatan' => $angkatan,
            'semester' => $semester,
            'status_mahasiswa' => $status_mahasiswa
        ]);
    }

    public function aktivitas_kuliah_data(Request $request)
    {
        $searchValue = $request->input('search.value');

        $query = AktivitasKuliahMahasiswa::with('pembiayaan')->join('pembiayaans', 'aktivitas_kuliah_mahasiswas.id_pembiayaan', 'pembiayaans.id_pembiayaan');

        if ($searchValue) {
            $query = $query->where('nim', 'like', '%' . $searchValue . '%')
                ->orWhere('nama_mahasiswa', 'like', '%' . $searchValue . '%');
        }

        if ($request->has('id_prodi') && !empty($request->id_prodi)) {
            $filter = $request->id_prodi;
            $query->whereIn('id_prodi', $filter);
        }

        if ($request->has('semester') && !empty($request->semester)) {
            $filter = $request->semester;
            $query->whereIn('id_semester', $filter);
        }

        if ($request->has('angkatan') && !empty($request->angkatan)) {
            $filter = $request->angkatan;
            $query->whereIn('angkatan', $filter);
        }

        if ($request->has('status_mahasiswa') && !empty($request->status_mahasiswa)) {
            $filter = $request->status_mahasiswa;
            $query->whereIn('id_status_mahasiswa', $filter);
        }

        $recordsFiltered = $query->count();

        $limit = $request->input('length');
        $offset = $request->input('start');

        // Define the column names that correspond to the DataTables column indices
        if ($request->has('order')) {
            $orderColumn = $request->input('order.0.column');
            $orderDirection = $request->input('order.0.dir');

            // Define the column names that correspond to the DataTables column indices
            $columns = ['nim','nama_mahasiswa', 'nama_program_studi', 'angkatan', 'nama_semester', 'nama_status_mahasiswa', 'ips', 'ipk', 'sks_semester', 'sks_total'];

            // if ($columns[$orderColumn] == 'prodi') {
            //     $query = $query->join('program_studis as prodi', 'mata_kuliahs.id_prodi', '=', 'prodi.id')
            //         ->orderBy('prodi.nama_jenjang_pendidikan', $orderDirection)
            //         ->orderBy('prodi.nama_program_studi', $orderDirection)
            //         ->select('mata_kuliahs.*', 'prodi.nama_jenjang_pendidikan', 'prodi.nama_program_studi'); // Avoid column name conflicts
            // } else {
                $query = $query->orderBy($columns[$orderColumn], $orderDirection);
            // }
        }

        $data = $query->skip($offset)->take($limit)->get();

        $recordsTotal = AktivitasKuliahMahasiswa::count();

        $response = [
            'draw' => $request->input('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ];

        return response()->json($response);
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

        $job = \App\Jobs\SyncJob::class;
        $model = \App\Models\Perkuliahan\AktivitasKuliahMahasiswa::class;
        $name = 'aktivitas-kuliah-mahasiswa';
        $primary = ['id_registrasi_mahasiswa', 'id_semester'];

        $batch = $this->sync2($act, $limit, $offset, $order, $job, $name, $model, $primary);

        return redirect()->back()->with('success', 'Sinkronisasi Kelas Kuliah Berhasil!');
    }


    public function aktivitas_mahasiswa(Request $request)
    {
        $request->validate([
            'semester' => 'nullable',
            'semester.*' => 'nullable|exists:semesters,id_semester'
        ]);

        $pilihan_semester = Semester::select('id_semester', 'nama_semester')->orderBy('id_semester', 'desc')->get();

        $semester_view = $request->semester_view  ?? [SemesterAktif::select('id_semester')->first()->id_semester];
        $prodi = ProgramStudi::select('id_prodi', 'kode_program_studi', 'nama_program_studi', 'nama_jenjang_pendidikan')->where('status', 'A')->orderBy('kode_program_studi')->get();
        // $data = AktivitasMahasiswa::with(['prodi'])->whereIn('id_semester', $semester_view)->get();
        $jenis_aktivitas = JenisAktivitasMahasiswa::select('id_jenis_aktivitas_mahasiswa', 'nama_jenis_aktivitas_mahasiswa')->get();
        return view('universitas.perkuliahan.aktivitas-mahasiswa.index', [
            'pilihan_semester' => $pilihan_semester,
            'semester_view' => $semester_view,
            // 'data' => $data,
            'prodi' => $prodi,
            'jenis_aktivitas' => $jenis_aktivitas
        ]);
    }

    public function aktivitas_mahasiswa_data(Request $request)
    {
        $searchValue = $request->input('search.value');

        $query = AktivitasMahasiswa::with(['anggota_aktivitas_personal'])->leftJoin('program_studis as prodi', 'aktivitas_mahasiswas.id_prodi', 'prodi.id_prodi');

        if ($searchValue) {
            $query = $query->where(function ($query) use ($searchValue) {
                $query->where('judul', 'like', '%' . $searchValue . '%')
                    ->orWhere('nama_jenis_aktivitas', 'like', '%' . $searchValue . '%')
                    ->orWhereHas('anggota_aktivitas_personal', function ($query) use ($searchValue) {
                        $query->where('nama_mahasiswa', 'like', '%' . $searchValue . '%')
                            ->orWhere('nim', 'like', '%' . $searchValue . '%');
                    });
            });
        }

        if ($request->has('id_prodi') && !empty($request->id_prodi)) {
            $filter = $request->id_prodi;
            $query->whereIn('aktivitas_mahasiswas.id_prodi', $filter);
        }

        if ($request->has('semester') && !empty($request->semester)) {
            $filter = $request->semester;
            $query->whereIn('id_semester', $filter);
        } else {
            $query->where('id_semester', SemesterAktif::first()->id_semester);
        }

        if($request->has('jenis') && !empty($request->jenis)){
            $filter = $request->jenis;
            $query->whereIn('id_jenis_aktivitas', $filter);
        }

        // if ($request->has('angkatan') && !empty($request->angkatan)) {
        //     $filter = $request->angkatan;
        //     $query->whereIn('angkatan', $filter);
        // }

        // if ($request->has('status_mahasiswa') && !empty($request->status_mahasiswa)) {
        //     $filter = $request->status_mahasiswa;
        //     $query->whereIn('id_status_mahasiswa', $filter);
        // }

        $recordsFiltered = $query->count();

        $limit = $request->input('length');
        $offset = $request->input('start');

        // Define the column names that correspond to the DataTables column indices
        if ($request->has('order')) {
            $orderColumn = $request->input('order.0.column');
            $orderDirection = $request->input('order.0.dir');

            // Define the column names that correspond to the DataTables column indices
            $columns = ['judul'];

            // if ($columns[$orderColumn] == 'prodi') {
            //     $query = $query->join('program_studis as prodi', 'mata_kuliahs.id_prodi', '=', 'prodi.id')
            //         ->orderBy('prodi.nama_jenjang_pendidikan', $orderDirection)
            //         ->orderBy('prodi.nama_program_studi', $orderDirection)
            //         ->select('mata_kuliahs.*', 'prodi.nama_jenjang_pendidikan', 'prodi.nama_program_studi'); // Avoid column name conflicts
            // } else {
                $query = $query->orderBy($columns[$orderColumn], $orderDirection);
            // }
        }

        $data = $query->skip($offset)->take($limit)->select('aktivitas_mahasiswas.*', 'prodi.nama_jenjang_pendidikan', 'prodi.nama_program_studi as nama_prodi', 'prodi.kode_program_studi as kode_prodi')->get();

        $recordsTotal = AktivitasMahasiswa::count();

        $response = [
            'draw' => $request->input('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ];

        return response()->json($response);
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

    public function nilai_perkuliahan()
    {
        $prodi = ProgramStudi::orderBy('kode_program_studi')->get();
        $semester = Semester::select('nama_semester', 'id_semester')->orderBy('id_semester', 'desc')->get();
        return view('universitas.perkuliahan.nilai-perkuliahan.index', compact('prodi', 'semester'));

    }

    public function sync_nilai_perkuliahan()
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '1G');

        $data = [
                    'act' => 'GetDetailNilaiPerkuliahanKelas',
                    'limit' => '',
                    'offset' => '',
                    'order' => '',
                    'job' => \App\Jobs\SyncJob::class,
                    'name' => 'nilai-perkuliahan',
                    'model' => \App\Models\Perkuliahan\NilaiPerkuliahan::class,
                    'primary' => ['id_kelas_kuliah', 'id_registrasi_mahasiswa'],
                ];

            $prodi = ProgramStudi::pluck('id_prodi')->toArray();
            $semester_aktif = SemesterAktif::first()->id_semester;
            $semester = Semester::whereNot('id_semester', $semester_aktif)->pluck('id_semester')->toArray();
            $semester = array_chunk($semester, 3);
            $semester = array_map(function ($value) {
                return "id_semester IN ('" . implode("','", $value) . "')";
            }, $semester);

            $batch = Bus::batch([])->name($data['name'])->dispatch();

            foreach ($prodi as $p) {
                foreach ($semester as $s) {
                    $filter = "id_prodi = '$p' AND $s";
                    // dd($filter);
                    $batch->add(new $data['job']($data['act'], $data['limit'], $data['offset'], $data['order'], $filter, $data['model'], $data['primary']));
                }
            }

        return redirect()->back()->with('success', 'Sinkronisasi Nilai Perkuliahan Berhasil!');
    }

    public function konversi_aktivitas()
    {
        return view('universitas.perkuliahan.konversi-aktivitas.index');
    }

    public function sync_konversi_aktivitas()
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '1G');

        $act = 'GetListKonversiKampusMerdeka';
        $count = $this->count_value('GetCountKonversiKampusMerdeka');
        $limit = 500;
        $order = 'id_konversi_aktivitas';

        $job = \App\Jobs\SyncJob::class;
        $name = 'konversi-aktivitas';
        $batch = Bus::batch([])->name($name)->dispatch();

        for ($i = 0; $i < $count; $i += 500) {
            $batch->add(new $job($act, $limit, $i, $order,'', \App\Models\Perkuliahan\KonversiAktivitas::class, 'id_konversi_aktivitas'));
        }

        return redirect()->back()->with('success', 'Sinkronisasi Konversi Aktivitas Berhasil!');
    }

    public function transkrip()
    {
        $jobData =  DB::table('job_batches')->where('name', 'transkrip-mahasiswa')->where('pending_jobs', '>', 0)->first();

        $statusSync = $jobData ? 1 : 0;

        $id_batch = $jobData ? $jobData->id : null;

        return view('universitas.perkuliahan.transkrip.index',[
            'statusSync' => $statusSync,
            'id_batch' => $id_batch
        ]);
    }

    public function sync_transkrip()
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '1G');

        $act = 'GetTranskripMahasiswa';

        TranskripMahasiswa::truncate();

        $matkul = MataKuliah::pluck('id_matkul')->toArray();
        $matkul = array_chunk($matkul, 12);
        $matkul = array_map(function ($value) {
            return "id_matkul IN ('" . implode("','", $value) . "')";
        }, $matkul);
        $name = 'transkrip-mahasiswa';
        $job = \App\Jobs\SyncJob::class;
        $limit = '';
        $offset = '';
        $order = '';
        $primary = ['id_registrasi_mahasiswa', 'id_matkul'];
        $model = \App\Models\Perkuliahan\TranskripMahasiswa::class;

        $batch = Bus::batch([])->name($name)->dispatch();

        foreach ($matkul as $s) {
            $filter = $s;
            $batch->add(new $job($act, $limit, $offset, $order, $filter, $model, $primary));
        }

        return redirect()->back()->with('success', 'Sinkronisasi Transkrip Mahasiswa Berhasil!');
    }

    public function sync_komponen_evaluasi_kelas()
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '1G');

        $act = 'GetListKomponenEvaluasiKelas';
        $limit = '';
        $offset = '';
        $order = '';
        $job = \App\Jobs\SyncJob::class;
        $name = 'komponen-evaluasi-kelas';
        $model = \App\Models\Perkuliahan\KomponenEvaluasiKelas::class;
        $primary = 'id_komponen_evaluasi';

        $kelas = KelasKuliah::where('id_semester', '>=', '20221')->pluck('id_kelas_kuliah')->toArray();
        $kelas = array_chunk($kelas, 15);
        $kelas = array_map(function ($value) {
            return "id_kelas_kuliah IN ('" . implode("','", $value) . "')";
        }, $kelas);

        $batch = Bus::batch([])->name($name)->dispatch();

        foreach ($kelas as $s) {
            $filter = $s;
            $batch->add(new $job($act, $limit, $offset, $order, $filter, $model, $primary));
        }

        return redirect()->back()->with('success', 'Sinkronisasi Komponen Evaluasi Kelas Berhasil!');


    }

    public function sync_nilai_komponen()
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '1G');

        $act = 'GetRiwayatNilaiMahasiswaKomponenEvaluasi';
        $limit = '';
        $offset = '';
        $order = '';
        $job = \App\Jobs\SyncJob::class;
        $name = 'nilai-komponen-evaluasi';
        $model = \App\Models\Perkuliahan\NilaiKomponenEvaluasi::class;
        $primary = ['id_registrasi_mahasiswa', 'id_komponen_evaluasi'];

        $data = KomponenEvaluasiKelas::pluck('id_komponen_evaluasi')->toArray();
        $data = array_chunk($data, 10);
        $data = array_map(function ($value) {
            return "id_komponen_evaluasi IN ('" . implode("','", $value) . "')";
        }, $data);

        $batch = Bus::batch([])->name($name)->dispatch();

        foreach ($data as $s) {
            $filter = $s;
            $batch->add(new $job($act, $limit, $offset, $order, $filter, $model, $primary));
        }

        return redirect()->back()->with('success', 'Sinkronisasi Nilai Komponen Evaluasi Berhasil!');
    }

}
