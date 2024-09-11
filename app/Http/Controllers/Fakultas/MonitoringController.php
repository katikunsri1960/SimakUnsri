<?php

namespace App\Http\Controllers\Fakultas;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa\LulusDo;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\MonitoringIsiKrs;
use App\Models\ProgramStudi;
use App\Models\SemesterAktif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class MonitoringController extends Controller
{
    public function pengisian_krs()
    {
        $id_prodi_fak = ProgramStudi::where('fakultas_id', auth()->user()->fk_id)
                    ->orderBy('id_jenjang_pendidikan')
                    ->orderBy('nama_program_studi')
                    ->pluck('id_prodi');

        $prodi = ProgramStudi::where('status', 'A')
                    ->whereIn('id_prodi', $id_prodi_fak)
                    ->orderBy('id_jenjang_pendidikan')
                    ->orderBy('nama_program_studi')
                    ->get();

        $data = MonitoringIsiKrs::with(['prodi'])->join('program_studis', 'monitoring_isi_krs.id_prodi', 'program_studis.id_prodi')
                                ->join('fakultas', 'fakultas.id', 'program_studis.fakultas_id')
                                ->orderBy('program_studis.fakultas_id')
                                ->orderBy('program_studis.kode_program_studi')
                                ->get();
        // DD($prodi);
        return view('fakultas.monitoring.pengisian-krs.index', [
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

        $id_prodi_fak = ProgramStudi::where('fakultas_id', auth()->user()->fk_id)
                    ->orderBy('id_jenjang_pendidikan')
                    ->orderBy('nama_program_studi')
                    ->pluck('id_prodi');

        $prodi = ProgramStudi::where('status', 'A')
                    ->whereIn('id_prodi', $id_prodi_fak)
                    ->orderBy('id_jenjang_pendidikan')
                    ->orderBy('nama_program_studi')
                    ->get();

        // DD($prodi);
        $db = new RiwayatPendidikan();

        $p = $prodi[$step];

        $totalProdi = $prodi->count();
        $currentProgress = 0;
        $index = 0;


        $baseQuery = $db->where('id_prodi', $p->id_prodi)
            ->whereNull('id_jenis_keluar');

        $jumlah_mahasiswa = (clone $baseQuery)->count();
        $jumlah_mahasiswa_now = (clone $baseQuery)
                                ->whereIn(DB::raw('LEFT(id_periode_masuk, 4)'), $arrayTahun)
                                ->count();

        $isi_krs = $db->where('id_prodi', $p->id_prodi)
                ->whereNull('id_jenis_keluar')
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

        $approve = $db->where('id_prodi', $p->id_prodi)
            ->whereNull('id_jenis_keluar')
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

        $non_approve = $db->where('id_prodi', $p->id_prodi)
            ->whereNull('id_jenis_keluar')
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
            ['id_prodi' => $p->id_prodi],
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

        $data = RiwayatPendidikan::where('id_prodi', $id_prodi)
                ->whereNull('id_jenis_keluar')
                ->orderBy('id_periode_masuk', 'ASC')
                ->get();

        return view('fakultas.monitoring.pengisian-krs.detail-mahasiswa-aktif', [
            'prodi' => $prodi,
            'data' => $data
        ]);
    }

    public function detail_aktif_min_tujuh(ProgramStudi $prodi)
    {
        $id_prodi = $prodi->id_prodi;

        $angkatanAktif = date('Y') - 7;
        $arrayTahun = range($angkatanAktif, date('Y'));

        $data = RiwayatPendidikan::where('id_prodi', $id_prodi)
                ->whereNull('id_jenis_keluar')
                ->whereIn(DB::raw('LEFT(id_periode_masuk, 4)'), $arrayTahun)
                ->orderBy('id_periode_masuk', 'ASC')
                ->get();

        return view('fakultas.monitoring.pengisian-krs.detail-aktif-min-tujuh', [
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

        return view('fakultas.monitoring.pengisian-krs.detail-isi-krs', [
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

        return view('fakultas.monitoring.pengisian-krs.approve-krs', [
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

        return view('fakultas.monitoring.pengisian-krs.not-approve-krs', [
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

        $id_prodi_fak = ProgramStudi::where('fakultas_id', auth()->user()->fk_id)
                    ->orderBy('id_jenjang_pendidikan')
                    ->orderBy('nama_program_studi')
                    ->pluck('id_prodi');

        $prodi = ProgramStudi::where('status', 'A')
                    ->whereIn('id_prodi', $id_prodi_fak)
                    ->orderBy('id_jenjang_pendidikan')
                    ->orderBy('nama_program_studi')
                    ->get();
                    
        $angkatan = $db->select('angkatan')->distinct()->orderBy('angkatan', 'desc')->get();

        return view('fakultas.monitoring.kelulusan.index', [
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
}
