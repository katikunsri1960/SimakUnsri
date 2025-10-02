<?php

namespace App\Http\Controllers\Universitas;

use Carbon\Carbon;
use App\Models\JobBatch;
use App\Models\Semester;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use App\Models\SemesterAktif;
use App\Models\PenundaanBayar;
use App\Models\MonitoringIsiKrs;
use App\Jobs\GenerateMonevStatus;
use App\Models\Mahasiswa\LulusDo;
use App\Models\Connection\Tagihan;
use App\Models\Dosen\BiodataDosen;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Bus;
use App\Http\Controllers\Controller;
use App\Models\Connection\Registrasi;
use App\Models\Fakultas;
use Illuminate\Support\Facades\Cache;
use App\Models\Perkuliahan\ListKurikulum;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Monitoring\MonevStatusMahasiswa;
use App\Models\Monitoring\MonevStatusMahasiswaDetail;

class MonitoringController extends Controller
{

    public function upload_feeder()
    {
        $semester = Semester::orderBy('id_semester', 'desc')->get();

        $semester = '20241';

        $result = [];

        $prodi = ProgramStudi::where('status', 'A')
            ->select('id_prodi', 'kode_program_studi', 'nama_jenjang_pendidikan', 'nama_program_studi')
            ->withCount([
                'aktivitas_kuliah as akm_feeder_1' => function ($query) use ($semester) {
                    $query->where('id_semester', $semester)->where('feeder', 1);
                },
                'aktivitas_kuliah as akm_feeder_0' => function ($query) use ($semester) {
                    $query->where('id_semester', $semester)->where('feeder', 0);
                }
            ])
            ->get();

        foreach ($prodi as $p) {
            $result[] = [
                'id_prodi' => $p->id_prodi,
                'kode_program_studi' => $p->kode_program_studi,
                'nama_prodi' => $p->nama_jenjang_pendidikan . ' - ' . $p->nama_program_studi,
                'akm' => $p->akm,
                'akm_feeder_1' => $p->akm_feeder_1,
                'akm_feeder_0' => $p->akm_feeder_0,
            ];
        }

        // dd($result);

        return view('universitas.monitoring.upload-feeder.index', [
            'semester' => $semester
        ]);
    }

    public function upload_feeder_data(Request $request)
    {
        $data = $request->validate([
            'semester' => 'required|exists:semesters,id_semester',
        ]);

        $semester = $data['semester'];

        $result = [];

        $prodi = ProgramStudi::where('status', 'A')
            ->select('id_prodi', 'kode_program_studi', 'nama_jenjang_pendidikan', 'nama_program_studi')
            ->withCount([
                'aktivitasKuliahMahasiswa as akm' => function ($query) use ($semester) {
                    $query->where('id_semester', $semester);
                },
                'aktivitasKuliahMahasiswa as akm_feeder_1' => function ($query) use ($semester) {
                    $query->where('id_semester', $semester)->where('feeder', 1);
                },
                'aktivitasKuliahMahasiswa as akm_feeder_0' => function ($query) use ($semester) {
                    $query->where('id_semester', $semester)->where('feeder', 0);
                }
            ])
            ->get();

        foreach ($prodi as $p) {
            $result[] = [
                'id_prodi' => $p->id_prodi,
                'kode_program_studi' => $p->kode_program_studi,
                'nama_prodi' => $p->nama_jenjang_pendidikan . ' - ' . $p->nama_program_studi,
                'akm' => $p->akm,
                'akm_feeder_1' => $p->akm_feeder_1,
                'akm_feeder_0' => $p->akm_feeder_0,
            ];
        }

        return response()->json($result);
    }

    public function pengisian_krs()
    {
        $prodi = ProgramStudi::where('status', 'A')->orderBy('id')->get();
        $semesterAktif = SemesterAktif::first()->id_semester;

        $data = MonitoringIsiKrs::with(['prodi'])->join('program_studis', 'monitoring_isi_krs.id_prodi', 'program_studis.id_prodi')
                                ->join('fakultas', 'fakultas.id', 'program_studis.fakultas_id')
                                ->orderBy('program_studis.fakultas_id')
                                ->orderBy('program_studis.kode_program_studi')
                                ->where('monitoring_isi_krs.id_semester', $semesterAktif)
                                ->get();

        return view('universitas.monitoring.pengisian-krs.index', [
            'prodi' => $prodi,
            'data' => $data
        ]);
    }

    public function generateDataIsiKrs(Request $request)
    {
        $step = $request->input('step', 0);
        $angkatanAktif = date('Y') - 7;
        $arrayTahun = range($angkatanAktif, date('Y'));

        $semesterAktif = SemesterAktif::first()->id_semester;

        $prodi = ProgramStudi::where('status', 'A')->orderBy('id')->get();

        $db = new RiwayatPendidikan();

        $p = $prodi[$step];

        $totalProdi = $prodi->count();
        $currentProgress = 0;
        $index = 0;


        $baseQuery = $db->whereDoesntHave('lulus_do')
                ->where('id_prodi', $p->id_prodi);

        $jumlah_mahasiswa = (clone $baseQuery)->count();
        $jumlah_mahasiswa_now = (clone $baseQuery)
                                ->whereIn(DB::raw('LEFT(id_periode_masuk, 4)'), $arrayTahun)
                                ->count();

        $isi_krs = $db->whereDoesntHave('lulus_do')
                ->where('id_prodi', $p->id_prodi)
                ->where(function ($query) use ($semesterAktif) {
                    $query->whereExists(function ($subquery) use ($semesterAktif) {
                        $subquery->select(DB::raw(1))
                            ->from('peserta_kelas_kuliahs as p')
                            ->join('kelas_kuliahs as k', 'p.id_kelas_kuliah', '=', 'k.id_kelas_kuliah')
                            ->where('k.id_semester', $semesterAktif)
                            ->whereColumn('p.id_registrasi_mahasiswa', 'riwayat_pendidikans.id_registrasi_mahasiswa');
                    })
                    ->orWhere(function ($query) use ($semesterAktif) {
                        $query->whereNotExists(function ($subquery) use ($semesterAktif) {
                            $subquery->select(DB::raw(1))
                                ->from('peserta_kelas_kuliahs as p')
                                ->join('kelas_kuliahs as k', 'p.id_kelas_kuliah', '=', 'k.id_kelas_kuliah')
                                ->where('k.id_semester', $semesterAktif)
                                ->whereColumn('p.id_registrasi_mahasiswa', 'riwayat_pendidikans.id_registrasi_mahasiswa');
                        })
                        ->whereExists(function ($subquery) use ($semesterAktif) {
                            $subquery->select(DB::raw(1))
                                ->from('anggota_aktivitas_mahasiswas as aam')
                                ->join('aktivitas_mahasiswas as a', 'aam.id_aktivitas', '=', 'a.id_aktivitas')
                                ->where('a.id_semester', $semesterAktif)
                                ->whereIn('a.id_jenis_aktivitas', [1,2,3,4,5,6,13,14,15,16,17,18,19,20,21,22])
                                ->whereColumn('aam.id_registrasi_mahasiswa', 'riwayat_pendidikans.id_registrasi_mahasiswa');
                        });
                    });
                })
                ->distinct()
                ->count();

        $approve = $db->whereDoesntHave('lulus_do')
                ->where('id_prodi', $p->id_prodi)
                ->where(function ($query) use ($semesterAktif) {
                $query->whereExists(function ($subquery) use ($semesterAktif) {
                    $subquery->select(DB::raw(1))
                        ->from('peserta_kelas_kuliahs as p')
                        ->join('kelas_kuliahs as k', 'p.id_kelas_kuliah', '=', 'k.id_kelas_kuliah')
                        ->where('k.id_semester', $semesterAktif)
                        ->where('p.approved', '1')
                        ->whereColumn('p.id_registrasi_mahasiswa', 'riwayat_pendidikans.id_registrasi_mahasiswa');
                })
                ->orWhere(function ($query) use ($semesterAktif) {
                    $query->whereNotExists(function ($subquery) use ($semesterAktif) {
                        $subquery->select(DB::raw(1))
                            ->from('peserta_kelas_kuliahs as p')
                            ->join('kelas_kuliahs as k', 'p.id_kelas_kuliah', '=', 'k.id_kelas_kuliah')
                            ->where('k.id_semester', $semesterAktif)
                            ->where('p.approved', '1')
                            ->whereColumn('p.id_registrasi_mahasiswa', 'riwayat_pendidikans.id_registrasi_mahasiswa');
                    })
                    ->whereExists(function ($subquery) use ($semesterAktif) {
                        $subquery->select(DB::raw(1))
                            ->from('anggota_aktivitas_mahasiswas as aam')
                            ->join('aktivitas_mahasiswas as a', 'aam.id_aktivitas', '=', 'a.id_aktivitas')
                            ->where('a.id_semester', $semesterAktif)
                            ->where('a.approve_krs', '1')
                            ->whereIn('a.id_jenis_aktivitas', [1,2,3,4,5,6,13,14,15,16,17,18,19,20,21,22])
                            ->whereColumn('aam.id_registrasi_mahasiswa', 'riwayat_pendidikans.id_registrasi_mahasiswa');
                    });
                });
            })
            ->distinct()
            ->count();

        $non_approve = $db->whereDoesntHave('lulus_do')
            ->where('id_prodi', $p->id_prodi)
            ->where(function ($query) use ($semesterAktif) {
                $query->whereExists(function ($subquery) use ($semesterAktif) {
                    $subquery->select(DB::raw(1))
                        ->from('peserta_kelas_kuliahs as p')
                        ->join('kelas_kuliahs as k', 'p.id_kelas_kuliah', '=', 'k.id_kelas_kuliah')
                        ->where('k.id_semester', $semesterAktif)
                        ->where('p.approved', '0')
                        ->whereColumn('p.id_registrasi_mahasiswa', 'riwayat_pendidikans.id_registrasi_mahasiswa');
                })
                ->orWhere(function ($query) use ($semesterAktif) {
                    $query->whereNotExists(function ($subquery) use ($semesterAktif) {
                        $subquery->select(DB::raw(1))
                            ->from('peserta_kelas_kuliahs as p')
                            ->join('kelas_kuliahs as k', 'p.id_kelas_kuliah', '=', 'k.id_kelas_kuliah')
                            ->where('k.id_semester', $semesterAktif)
                            ->where('p.approved', '0')
                            ->whereColumn('p.id_registrasi_mahasiswa', 'riwayat_pendidikans.id_registrasi_mahasiswa');
                    })
                    ->whereExists(function ($subquery) use ($semesterAktif) {
                        $subquery->select(DB::raw(1))
                            ->from('anggota_aktivitas_mahasiswas as aam')
                            ->join('aktivitas_mahasiswas as a', 'aam.id_aktivitas', '=', 'a.id_aktivitas')
                            ->where('a.id_semester', $semesterAktif)
                            ->where('a.approve_krs', '0')
                            ->whereIn('a.id_jenis_aktivitas', [1,2,3,4,5,6,13,14,15,16,17,18,19,20,21,22])
                            ->whereColumn('aam.id_registrasi_mahasiswa', 'riwayat_pendidikans.id_registrasi_mahasiswa');
                    });
                });
            })
            ->distinct()
            ->count();

        MonitoringIsiKrs::updateOrCreate(
            [
                'id_semester' => $semesterAktif
                ,'id_prodi' => $p->id_prodi
            ],
            [
                'mahasiswa_aktif' => $jumlah_mahasiswa,
                'mahasiswa_aktif_min_7' => $jumlah_mahasiswa_now,
                'isi_krs' => $isi_krs,
                'krs_approved' => $approve,
                'krs_not_approved' => $non_approve
            ]
        );

        // Update progress
        $progress = (($step + 1) / count($prodi)) * 100;

        return response()->json([
            'progress' => $progress,
            'completed' => ($step + 1) == count($prodi) // True jika selesai
        ]);
    }

    public function checkProgress()
    {
        $progress = Cache::get('progress_krs', 0); // Ambil progress dari cache
        return response()->json($progress);
    }

    public function pengisian_krs_data(Request $request)
    {
        $searchValue = $request->input('search.value');

        $angkatanAktif = date('Y') - 7;
        $arrayTahun = range($angkatanAktif, date('Y'));
        $angkatanAktif = implode(',', $arrayTahun);

        $semesterAktif = SemesterAktif::first()->id_semester;
        $query = ProgramStudi::select(
                        'program_studis.id_prodi', 'program_studis.id as id',
                        'fakultas.nama_fakultas',
                        DB::raw('CONCAT(program_studis.nama_jenjang_pendidikan, " - ", program_studis.nama_program_studi) AS nama_prodi')
                    )
                    ->join('fakultas', 'program_studis.fakultas_id', '=', 'fakultas.id')
                    ->where('program_studis.status', 'A');
                // ->orderBy('fakultas.id');

        $query->addSelect(DB::raw("
                (SELECT COUNT(DISTINCT riwayat_pendidikans.id_registrasi_mahasiswa)
                 FROM riwayat_pendidikans
                 WHERE riwayat_pendidikans.id_prodi = program_studis.id_prodi
                   AND riwayat_pendidikans.id_jenis_keluar IS NULL) AS jumlah_mahasiswa
            "));

        $query->addSelect(DB::raw("
            (SELECT COUNT(DISTINCT riwayat_pendidikans.id_registrasi_mahasiswa)
             FROM riwayat_pendidikans
             WHERE riwayat_pendidikans.id_prodi = program_studis.id_prodi AND LEFT(riwayat_pendidikans.id_periode_masuk, 4) IN (".$angkatanAktif.")
               AND riwayat_pendidikans.id_jenis_keluar IS NULL) AS jumlah_mahasiswa_now
        "));

        $query->addSelect(DB::raw("
            (CASE
                WHEN EXISTS (
                      SELECT 1
                        FROM peserta_kelas_kuliahs
                        JOIN riwayat_pendidikans ON peserta_kelas_kuliahs.id_registrasi_mahasiswa = riwayat_pendidikans.id_registrasi_mahasiswa
                        JOIN kelas_kuliahs ON peserta_kelas_kuliahs.id_kelas_kuliah = kelas_kuliahs.id_kelas_kuliah
                        WHERE kelas_kuliahs.id_semester = ".$semesterAktif."
                        AND riwayat_pendidikans.id_prodi = program_studis.id_prodi
                ) THEN (
                     SELECT COUNT(DISTINCT peserta_kelas_kuliahs.id_registrasi_mahasiswa)
                        FROM peserta_kelas_kuliahs
                        JOIN kelas_kuliahs ON peserta_kelas_kuliahs.id_kelas_kuliah = kelas_kuliahs.id_kelas_kuliah
                        WHERE kelas_kuliahs.id_semester = ".$semesterAktif."
                        AND peserta_kelas_kuliahs.id_prodi = program_studis.id_prodi
                )
                ELSE (
                    SELECT COUNT(DISTINCT anggota_aktivitas_mahasiswas.id_registrasi_mahasiswa)
                    FROM anggota_aktivitas_mahasiswas
                    JOIN aktivitas_mahasiswas ON anggota_aktivitas_mahasiswas.id_aktivitas = aktivitas_mahasiswas.id_aktivitas
                    WHERE aktivitas_mahasiswas.id_semester = ".$semesterAktif."
                    AND aktivitas_mahasiswas.id_jenis_aktivitas IN (1,2,3,4,5,6,13,14,15,16,17,18,19,20,21,22)
                    AND aktivitas_mahasiswas.id_prodi = program_studis.id_prodi
                )
            END) AS jumlah_mahasiswa_isi_krs
        "));

        $query->addSelect(DB::raw("
            (CASE
                WHEN EXISTS (
                    SELECT 1 FROM peserta_kelas_kuliahs,riwayat_pendidikans,kelas_kuliahs
                    WHERE peserta_kelas_kuliahs.id_registrasi_mahasiswa = riwayat_pendidikans.id_registrasi_mahasiswa
                    AND peserta_kelas_kuliahs.id_kelas_kuliah=kelas_kuliahs.id_kelas_kuliah
                    AND kelas_kuliahs.id_semester= ".$semesterAktif."
                    AND peserta_kelas_kuliahs.approved = '1'
                ) THEN (
                     SELECT COUNT(DISTINCT peserta_kelas_kuliahs.id_registrasi_mahasiswa)
                    FROM peserta_kelas_kuliahs, kelas_kuliahs
                    WHERE peserta_kelas_kuliahs.id_kelas_kuliah = kelas_kuliahs.id_kelas_kuliah
                    AND peserta_kelas_kuliahs.id_prodi = program_studis.id_prodi
                    AND kelas_kuliahs.id_semester = ".$semesterAktif."
                    AND peserta_kelas_kuliahs.approved = '1'
                )
                ELSE (
                    SELECT COUNT(DISTINCT anggota_aktivitas_mahasiswas.id_registrasi_mahasiswa)
                    FROM anggota_aktivitas_mahasiswas, aktivitas_mahasiswas
                    WHERE anggota_aktivitas_mahasiswas.id_aktivitas = aktivitas_mahasiswas.id_aktivitas
                    AND aktivitas_mahasiswas.id_semester = ".$semesterAktif."
                    AND aktivitas_mahasiswas.approve_krs = '1'
                    AND aktivitas_mahasiswas.id_jenis_aktivitas IN (1,2,3,4,5,6,13,14,15,16,17,18,19,20,21,22)
                )
            END) AS jumlah_mahasiswa_approved
        "));

        $query->addSelect(DB::raw("
            (CASE
                WHEN EXISTS (
                    SELECT 1 FROM peserta_kelas_kuliahs,riwayat_pendidikans,kelas_kuliahs
                    WHERE peserta_kelas_kuliahs.id_registrasi_mahasiswa = riwayat_pendidikans.id_registrasi_mahasiswa
                    AND peserta_kelas_kuliahs.id_kelas_kuliah=kelas_kuliahs.id_kelas_kuliah
                    AND kelas_kuliahs.id_semester= ".$semesterAktif."
                    AND peserta_kelas_kuliahs.approved = '0'
                ) THEN (
                       SELECT COUNT(DISTINCT peserta_kelas_kuliahs.id_registrasi_mahasiswa)
                    FROM peserta_kelas_kuliahs, kelas_kuliahs
                    WHERE peserta_kelas_kuliahs.id_kelas_kuliah = kelas_kuliahs.id_kelas_kuliah
                    AND peserta_kelas_kuliahs.id_prodi = program_studis.id_prodi
                    AND kelas_kuliahs.id_semester = ".$semesterAktif."
                    AND peserta_kelas_kuliahs.approved = '0'
                )
                ELSE (
                    SELECT COUNT(DISTINCT anggota_aktivitas_mahasiswas.id_registrasi_mahasiswa)
                    FROM anggota_aktivitas_mahasiswas, aktivitas_mahasiswas
                    WHERE anggota_aktivitas_mahasiswas.id_aktivitas = aktivitas_mahasiswas.id_aktivitas
                    AND aktivitas_mahasiswas.id_semester = ".$semesterAktif."
                    AND aktivitas_mahasiswas.approve_krs = '0'
                    AND aktivitas_mahasiswas.id_jenis_aktivitas IN (1,2,3,4,5,6,13,14,15,16,17,18,19,20,21,22)
                )
            END) AS jumlah_mahasiswa_not_approved
        "));

        if ($searchValue) {
            $query = $query->where('nim', 'like', '%' . $searchValue . '%')
                ->orWhere('nama_mahasiswa', 'like', '%' . $searchValue . '%');
        }

        $recordsFiltered = $query->count();

        $limit = $request->input('length');
        $offset = $request->input('start');

        $data = $query->skip($offset)->take($limit)->get();

        $data->each(function ($item, $key) use ($offset) {
            $item->DT_RowIndex = $offset + $key + 1;
        });

        $recordsTotal = $data->count();

        $response = [
            'draw' => $request->input('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ];

        return response()->json($response);
    }

    public function detail_mahasiswa_aktif(ProgramStudi $prodi)
    {
        $id_prodi = $prodi->id_prodi;

        $data = RiwayatPendidikan::whereDoesntHave('lulus_do')
                // ->whereHas('lulus_do') // hanya ambil yang ada relasi lulus_do
                ->where('id_prodi', $id_prodi)
                ->orderBy('id_periode_masuk', 'ASC')
                ->get();

        // dd($data);
        return view('universitas.monitoring.pengisian-krs.detail-mahasiswa-aktif', [
            'prodi' => $prodi,
            'data' => $data
        ]);
    }

    public function detail_aktif_min_tujuh(ProgramStudi $prodi)
    {
        $id_prodi = $prodi->id_prodi;

        $angkatanAktif = date('Y') - 7;
        $arrayTahun = range($angkatanAktif, date('Y'));

        $data = RiwayatPendidikan::whereDoesntHave('lulus_do')
                // ->whereHas('lulus_do')
                ->where('id_prodi', $id_prodi)
                // ->whereNull('id_jenis_keluar')
                ->whereIn(DB::raw('LEFT(id_periode_masuk, 4)'), $arrayTahun)
                ->orderBy('id_periode_masuk', 'ASC')
                ->get();

        return view('universitas.monitoring.pengisian-krs.detail-aktif-min-tujuh', [
            'prodi' => $prodi,
            'data' => $data
        ]);
    }

    public function detail_isi_krs(ProgramStudi $prodi)
    {
        $id_prodi = $prodi->id_prodi;
        $semesterAktif = SemesterAktif::first()->id_semester;

        $db = new RiwayatPendidikan();

        $data = $db->detail_isi_krs($id_prodi, $semesterAktif);

        return view('universitas.monitoring.pengisian-krs.detail-isi-krs', [
            'prodi' => $prodi,
            'data' => $data
        ]);
    }

    public function detail_approved_krs(ProgramStudi $prodi)
    {
        $id_prodi = $prodi->id_prodi;
        $semesterAktif = SemesterAktif::first()->id_semester;
        $db = new RiwayatPendidikan();

        $data = $db->krs_data($id_prodi, $semesterAktif, '1');

        return view('universitas.monitoring.pengisian-krs.approve-krs', [
            'prodi' => $prodi,
            'data' => $data
        ]);
    }

    public function detail_not_approved_krs(ProgramStudi $prodi)
    {
        $id_prodi = $prodi->id_prodi;
        $semesterAktif = SemesterAktif::first()->id_semester;
        $db = new RiwayatPendidikan();
        $data = $db->krs_data($id_prodi, $semesterAktif, 0);

        return view('universitas.monitoring.pengisian-krs.not-approve-krs', [
            'prodi' => $prodi,
            'data' => $data
        ]);
    }

    public function lulus_do(Request $request)
    {
        $db = new LulusDo();
        $jenis_keluar = $db->select('id_jenis_keluar', 'nama_jenis_keluar')->distinct()->get();

        $jenis_keluar_counts = $db->select('id_jenis_keluar','nama_jenis_keluar', DB::raw('count(*) as total'))
        ->groupBy('id_jenis_keluar','nama_jenis_keluar');

        if ($request->has('id_prodi') && !empty($request->id_prodi)) {
            $filter = $request->id_prodi;
            $jenis_keluar_counts->whereIn('id_prodi', $filter);
        }

        if ($request->has('angkatan') && !empty($request->angkatan)) {
            $filter = $request->angkatan;
            $jenis_keluar_counts->whereIn('angkatan', $filter);
        }

        if($request->has('jenis_keluar') && !empty($request->jenis_keluar)) {
            $filter = $request->jenis_keluar;
            $jenis_keluar_counts->whereIn('id_jenis_keluar', $filter);
        }

        $jenis_keluar_counts = $jenis_keluar_counts->get();

        $prodi = ProgramStudi::orderBy('kode_program_studi')->get();
        $angkatan = $db->select('angkatan')->distinct()->orderBy('angkatan', 'desc')->get();

        return view('universitas.monitoring.kelulusan.index', [
            'jenis_keluar' => $jenis_keluar,
            'jenis_keluar_counts' => $jenis_keluar_counts,
            'prodi' => $prodi,
            'angkatan' => $angkatan
        ]);
    }

    public function lulus_do_data(Request $request)
    {
        $searchValue = $request->input('search.value');

        $query = LulusDo::with('prodi', 'biodata');

        if ($searchValue) {
            $query = $query->where('nim', 'like', '%' . $searchValue . '%')
                ->orWhere('nama_mahasiswa', 'like', '%' . $searchValue . '%')
                ->orWhere('nama_program_studi', 'like', '%' . $searchValue . '%');
        }

        if ($request->has('id_prodi') && !empty($request->id_prodi)) {
            $filter = $request->id_prodi;
            $query->whereIn('id_prodi', $filter);
        }

        if ($request->has('angkatan') && !empty($request->angkatan)) {
            $filter = $request->angkatan;
            $query->whereIn('angkatan', $filter);
        }

        if($request->has('jenis_keluar') && !empty($request->jenis_keluar)) {
            $filter = $request->jenis_keluar;
            $query->whereIn('id_jenis_keluar', $filter);
        }

        $recordsFiltered = $query->count();

        $limit = $request->input('length');
        $offset = $request->input('start');

        // Define the column names that correspond to the DataTables column indices
        if ($request->has('order')) {
            $orderColumn = $request->input('order.0.column');
            $orderDirection = $request->input('order.0.dir');

            // Define the column names that correspond to the DataTables column indices
            $columns = ['nim','nama_mahasiswa', 'nama_program_studi', 'angkatan', 'tanggal_keluar', 'nm_smt', 'keterangan'];

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

        $recordsTotal = LulusDo::count();

        $response = [
            'draw' => $request->input('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ];

        return response()->json($response);
    }

    public function status_mahasiswa()
    {
        $prodi = ProgramStudi::where('status', 'A')->orderBy('id')->get();
        $semesterAktif = SemesterAktif::first()->id_semester;

            $riwayat = RiwayatPendidikan::
            // whereNull('id_jenis_keluar')
            // ->
            where('id_prodi', '2282d1e5-9e12-4c79-a33f-5579763f7f94')
            // ->whereIn('id_registrasi_mahasiswa', ['30a9d6c5-d5ba-478b-b448-efe9d8ef1bcb','1c5768b1-bd8a-4dd6-a3af-e74bb603130d'])
            ->select('id_registrasi_mahasiswa', 'id_prodi', 'id_periode_masuk')
            ->with('prodi')
            ->whereDoesntHave('lulus_do')
            ->withSum('transkrip_mahasiswa as total_sks', 'sks_mata_kuliah')
            ->get();

        // dd($riwayat);

        $db = new MonevStatusMahasiswa();

        $data = $db->with(['prodi.fakultas', 'details', 'semester'])->where('id_semester', $semesterAktif)->get();

        return view('universitas.monitoring.status-mahasiswa.index', [
            'data' => $data,
            'prodi' => $prodi
        ]);
    }

    public function generate_status_mahasiswa()
    {
        // dd('generate status mahasiswa');
        // make batch job to generate status mahasiswa
        $prodi = ProgramStudi::where('status', 'A')->get();
        $batch = Bus::batch([])->name('generate-monev-status')->dispatch();

        foreach ($prodi as $p) {
            $batch->add(new GenerateMonevStatus($p->id_prodi, 'mahasiswa_lewat_semester'));
        }

        return redirect()->route('univ.monitoring.status-mahasiswa')->with('success', 'Proses generate status mahasiswa sedang berjalan');
    }

    public function detail_total_status_mahasiswa($semester, $status)
    {

        $data = MonevStatusMahasiswaDetail::whereHas('monevStatusMahasiswa', function ($query) use ($semester) {
            $query->where('id_semester', $semester);
        })->where('status', $status) ->with(['riwayat.transkrip_mahasiswa' => function ($query) {
            $query->whereNotIn('nilai_huruf', ['F', '']);
        }])->get();


        foreach ($data as $d) {
            $total_sks = 0;
            $ipk = 0;
            $masa_studi = 0;


            if ($d->riwayat->transkrip_mahasiswa->count() > 0) {

                $total_sks = $d->riwayat->transkrip_mahasiswa->sum('sks_mata_kuliah');

                $total_bobot_transkrip = 0;

                foreach ($d->riwayat->transkrip_mahasiswa as $t) {
                    $total_bobot_transkrip += $t->nilai_indeks * $t->sks_mata_kuliah;
                }

                if($total_sks > 0){
                    $ipk = $total_bobot_transkrip / $total_sks;
                }

                $d->total_sks = $total_sks;
                $d->ipk = number_format($ipk, 2);

            } else {
                $d->total_sks = 'Tidak Ada Transkrip';
                $d->ipk = 'Tidak Ada Transkrip';
            }

            $now = $d->created_at;

            // Validasi data sebelum perhitungan
            if (!isset($d->riwayat->periode_masuk->semester) || !isset($d->riwayat->periode_masuk->id_tahun_ajaran)) {
                $masa_studi = 'Data Tidak Valid';
            } else {
                // Konstanta untuk tanggal default
                $SEMESTER_1_START = '-08-01';
                $SEMESTER_2_START = '-01-01';

                // Hitung tanggal masuk berdasarkan semester
                if ($d->riwayat->periode_masuk->semester == 2) {
                    $tanggal_masuk = Carbon::parse($d->riwayat->periode_masuk->id_tahun_ajaran + 1 . $SEMESTER_2_START);
                } elseif ($d->riwayat->periode_masuk->semester == 1) {
                    $tanggal_masuk = Carbon::parse($d->riwayat->periode_masuk->id_tahun_ajaran . $SEMESTER_1_START);
                } else {
                    $masa_studi = 'Data Tidak Valid';
                }

                // Hitung masa studi jika tanggal masuk valid
                if (isset($tanggal_masuk)) {
                    $masa_studi = floor($tanggal_masuk->diffInMonths($now));
                }
            }

            // Tetapkan masa studi ke objek
            $d->masa_studi = $masa_studi;

        }

        return view('universitas.monitoring.status-mahasiswa.detail-total', [
            'data' => $data,
            'status' => $status
        ]);
    }

    public function detail_prodi_status_mahasiswa(int $id, string $status)
    {
        $data = MonevStatusMahasiswaDetail::where('monev_status_mahasiswa_id', $id)
                ->where('status', $status)
                ->with(['riwayat.prodi', 'riwayat.periode_masuk'])
                ->with(['riwayat.transkrip_mahasiswa' => function ($query) {
                    $query->whereNotIn('nilai_huruf', ['F', '']);
                }])
                ->get();

        foreach ($data as $d) {
            $total_sks = 0;
            $ipk = 0;
            $masa_studi = 0;


            if ($d->riwayat->transkrip_mahasiswa->count() > 0) {

                $total_sks = $d->riwayat->transkrip_mahasiswa->sum('sks_mata_kuliah');

                $total_bobot_transkrip = 0;

                foreach ($d->riwayat->transkrip_mahasiswa as $t) {
                    $total_bobot_transkrip += $t->nilai_indeks * $t->sks_mata_kuliah;
                }

                if($total_sks > 0){
                    $ipk = $total_bobot_transkrip / $total_sks;
                }

                $d->total_sks = $total_sks;
                $d->ipk = number_format($ipk, 2);

            } else {
                $d->total_sks = 'Tidak Ada Transkrip';
                $d->ipk = 'Tidak Ada Transkrip';
            }

            $now = $d->created_at;

            // Validasi data sebelum perhitungan
            if (!isset($d->riwayat->periode_masuk->semester) || !isset($d->riwayat->periode_masuk->id_tahun_ajaran)) {
                $masa_studi = 'Data Tidak Valid';
            } else {
                // Konstanta untuk tanggal default
                $SEMESTER_1_START = '-08-01';
                $SEMESTER_2_START = '-01-01';

                // Hitung tanggal masuk berdasarkan semester
                if ($d->riwayat->periode_masuk->semester == 2) {
                    $tanggal_masuk = Carbon::parse($d->riwayat->periode_masuk->id_tahun_ajaran + 1 . $SEMESTER_2_START);
                } elseif ($d->riwayat->periode_masuk->semester == 1) {
                    $tanggal_masuk = Carbon::parse($d->riwayat->periode_masuk->id_tahun_ajaran . $SEMESTER_1_START);
                } else {
                    $masa_studi = 'Data Tidak Valid';
                }

                // Hitung masa studi jika tanggal masuk valid
                if (isset($tanggal_masuk)) {
                    $masa_studi = floor($tanggal_masuk->diffInMonths($now));
                }
            }

            // Tetapkan masa studi ke objek
            $d->masa_studi = $masa_studi;

        }



        return view('universitas.monitoring.status-mahasiswa.detail-prodi', [
            'data' => $data,
            'status' => $status
        ]);
    }

    public function getUnfinishedBatches()
    {
        $batches = JobBatch::whereNull('finished_at')
                ->orderByDesc('created_at')
                ->get();


        $data = [];

        foreach ($batches as $batch) {
            $busBatch = Bus::findBatch($batch->id);

            if ($busBatch) {
                $data[] = [
                    'id' => $batch->id,
                    'name' => $batch->name,
                    'progress' => $busBatch->progress() ?? 0,
                    'processed_jobs' => $busBatch->processedJobs(),
                    'total_jobs' => $busBatch->totalJobs,
                    'failed_jobs' => $busBatch->failedJobs,
                    'status' => $busBatch->cancelled() ? 'cancelled' : ($busBatch->finished() ? 'finished' : 'running'),
                    'created_at' => $batch->created_at->toDateTimeString(),
                ];
            }
        }

        return response()->json($data);
    }

    // public function status_ukt()
    // {
    //     // $prodi = ProgramStudi::where('status', 'A')->orderBy('id')->get();
    //     // $semesterAktif = SemesterAktif::first()->id_semester;

    //     // $db = new MonevStatusMahasiswa();

    //     // $data = $db->with(['prodi.fakultas', 'details', 'semester'])->where('id_semester', $semesterAktif)->get();

    //     // return view('universitas.monitoring.status-mahasiswa.index', [
    //     //     'data' => $data,
    //     //     'prodi' => $prodi
    //     // ]);

    //     return view('universitas.monitoring.status-ukt.index');
    // }

    public function status_ukt(Request $request)
    {
        $fakultas = Fakultas::all();

        // Ambil fakultas yang dipilih dari request (single select)
        $filterFakultas = $request->get('fakultas');

        // Filter prodi berdasarkan fakultas (jika dipilih)
        $prodi_fak = ProgramStudi::where('status', 'A')
                    ->when($filterFakultas, function ($q) use ($filterFakultas) {
                        $q->where('fakultas_id', $filterFakultas);
                    })
                    ->orderBy('id_jenjang_pendidikan')
                    ->orderBy('nama_program_studi')
                    ->get();

        $id_prodi_fak = $prodi_fak->pluck('id_prodi');

        // Ambil data angkatan berdasarkan prodi hasil filter
        $angkatan = RiwayatPendidikan::with(['prodi'])
                    ->whereIn('id_prodi', $id_prodi_fak)
                    ->select(DB::raw('LEFT(id_periode_masuk, 4) as angkatan_raw'))
                    ->distinct()
                    ->orderBy('angkatan_raw', 'desc')
                    ->get();

        return view('universitas.monitoring.status-ukt.index', [
            'angkatan' => $angkatan,
            'prodi'    => $prodi_fak,
            'fakultas' => $fakultas,
            'fakultas_selected' => $filterFakultas, // bisa dipakai di view
        ]);
    }

    public function getProdi($fakultas_id)
    {
        $prodi = ProgramStudi::where('status', 'A')
                    ->where('fakultas_id', $fakultas_id)
                    ->orderBy('id_jenjang_pendidikan')
                    ->orderBy('nama_program_studi')
                    ->get();

        return response()->json($prodi);
    }


    public function status_ukt_data(Request $request)
    {
        $semesterAktif = SemesterAktif::first()->id_semester;

        $query = RiwayatPendidikan::whereDoesntHave('lulus_do')
            ->orderBy('nama_program_studi', 'ASC')
            ->orderBy('id_periode_masuk', 'desc');

        // Filter
        if ($request->filled('prodi')) {
            $query->where('id_prodi', $request->get('prodi'));
        }
        if ($request->filled('angkatan')) {
            $query->whereIn(DB::raw('LEFT(id_periode_masuk, 4)'), $request->get('angkatan'));
        }

        $data = $query->get();

        // Tambahan informasi
        foreach ($data as $value) {
            $value->rm_no_test = Registrasi::where('rm_nim', $value->nim)->pluck('rm_no_test')->first();

            $value->tagihan = Tagihan::with('pembayaran')
                ->whereIn('nomor_pembayaran', [$value->rm_no_test, $value->nim])
                ->where('kode_periode', $semesterAktif)
                ->first();

            $penundaan = PenundaanBayar::where('id_registrasi_mahasiswa', $value->id_registrasi_mahasiswa)
                ->where('id_semester', $semesterAktif)
                ->first();

            $value->penundaan_bayar = $penundaan ? 1 : 0;
            $value->batas_bayar = $penundaan?->batas_bayar;

            // Status final
            if ($value->beasiswa) {
                $value->status_pembayaran_final = 'beasiswa';
            } elseif ($value->tagihan && $value->tagihan->pembayaran) {
                $tanggalBayar = $value->tagihan->pembayaran->tanggal_pembayaran ?? null;
                if ($penundaan && $value->batas_bayar && $tanggalBayar > $value->batas_bayar) {
                    $value->status_pembayaran_final = 'lunas_terlambat';
                } else {
                    $value->status_pembayaran_final = 'lunas';
                }
            } elseif ($penundaan) {
                $value->status_pembayaran_final = 'penundaan';
            } else {
                $value->status_pembayaran_final = 'belum_bayar';
            }
        }

        // Filter status bayar (opsional)
        if ($request->filled('status_bayar')) {
            $data = $data->filter(fn($item) =>
                in_array($item->status_pembayaran_final, (array) $request->get('status_bayar'))
            )->values();
        }

        return response()->json($data);
    }

    public function batch_job()
    {
        return view('universitas.monitoring.sync-feeder.index');
    }
}
