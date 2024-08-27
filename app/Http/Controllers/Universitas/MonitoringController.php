<?php

namespace App\Http\Controllers\Universitas;

use App\Http\Controllers\Controller;
use App\Models\ProgramStudi;
use App\Models\SemesterAktif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MonitoringController extends Controller
{
    public function pengisian_krs()
    {
        $semester_aktif = SemesterAktif::first();

        // $data = ProgramStudi::with('fakultas')
        //         ->withCount([
        //             'mahasiswa as jumlah_mahasiswa' => function ($query) {
        //                 $query->whereNull('id_jenis_keluar');
        //             },
        //             'peserta_kelas as jumlah_mahasiswa_isi_krs' => function ($query) use ($semester_aktif) {
        //                 $query->whereHas('kelas_kuliah', function ($query) use ($semester_aktif) {
        //                     $query->where('id_semester', $semester_aktif->id_semester);
        //                 });
        //             },
        //             'peserta_kelas as jumlah_mahasiswa_approved' => function ($query) use ($semester_aktif) {
        //                 $query->whereHas('kelas_kuliah', function ($query) use ($semester_aktif) {
        //                     $query->where('id_semester', $semester_aktif->id_semester);
        //                 })->where('approved', '1');
        //             },
        //             'peserta_kelas as jumlah_mahasiswa_not_approved' => function ($query) use ($semester_aktif) {
        //                 $query->whereHas('kelas_kuliah', function ($query) use ($semester_aktif) {
        //                     $query->where('id_semester', $semester_aktif->id_semester);
        //                 })->where('approved', '0');
        //             }
        //         ])
        //         ->select('program_studis.*')
        //         ->selectRaw('CONCAT(program_studis.nama_jenjang_pendidikan, " - ", program_studis.nama_program_studi) AS nama_prodi')
        //         ->where('program_studis.status', 'A')
        //         ->orderBy('nama_prodi', 'ASC')
        //         ->limit(10)
        //         ->get();

        //     dd($data[0]);
        // $angkatanAktif = date('Y') - 7;

        // $arrayTahun = [];
        // for ($i = $angkatanAktif; $i <= date('Y'); $i++) {
        //     $arrayTahun[] = $i;
        // }
        // // $angkatanAktif = implode(',', $arrayTahun);

        // $query = ProgramStudi::with('fakultas')
        //         ->select('program_studis.*')
        //         ->selectRaw('CONCAT(program_studis.nama_jenjang_pendidikan, " - ", program_studis.nama_program_studi) AS nama_prodi')
        //             ->withCount([
        //                 'mahasiswa as jumlah_mahasiswa' => function ($query) {
        //                     $query->whereNull('id_jenis_keluar');
        //                 },
        //                 'mahasiswa as jumlah_mahasiswa_now' => function ($query) use ($arrayTahun) {
        //                     $query->whereNull('id_jenis_keluar')
        //                         ->whereIn(DB::raw('LEFT(id_periode_masuk, 4)'), $arrayTahun);
        //                 },
        //                 'peserta_kelas as jumlah_mahasiswa_isi_krs' => function ($query) use ($semester_aktif) {
        //                     $query->join('kelas_kuliahs as kk1', 'peserta_kelas_kuliahs.id_kelas_kuliah', '=', 'kk1.id_kelas_kuliah')
        //                         ->where('kk1.id_semester', $semester_aktif->id_semester)
        //                         ->distinct()
        //                         ->select(DB::raw('COUNT(DISTINCT peserta_kelas_kuliahs.id_registrasi_mahasiswa)'));
        //                 },
        //                 'peserta_kelas as jumlah_mahasiswa_approved' => function ($query) use ($semester_aktif) {
        //                     $query->join('kelas_kuliahs as kk2', 'peserta_kelas_kuliahs.id_kelas_kuliah', '=', 'kk2.id_kelas_kuliah')
        //                         ->where('kk2.id_semester', $semester_aktif->id_semester)
        //                         ->where('peserta_kelas_kuliahs.approved', '1')
        //                         ->distinct()
        //                         ->select(DB::raw('COUNT(DISTINCT peserta_kelas_kuliahs.id_registrasi_mahasiswa)'));
        //                 },
        //                 'peserta_kelas as jumlah_mahasiswa_not_approved' => function ($query) use ($semester_aktif) {
        //                     $query->join('kelas_kuliahs as kk3', 'peserta_kelas_kuliahs.id_kelas_kuliah', '=', 'kk3.id_kelas_kuliah')
        //                         ->where('kk3.id_semester', $semester_aktif->id_semester)
        //                         ->where('peserta_kelas_kuliahs.approved', '0')
        //                         ->distinct()
        //                         ->select(DB::raw('COUNT(DISTINCT peserta_kelas_kuliahs.id_registrasi_mahasiswa)'));
        //                 }
        //             ])
        //         ->where('program_studis.status', 'A')
        //         ->orderBy('fakultas_id')
        //         ->orderBy('kode_program_studi', 'ASC')
        //             ->limit(10)
        //             ->get();

        // // //         // Inspect the final query and its bindings
        //         dd($query);


        return view('universitas.monitoring.pengisian-krs.index', [
            // 'data' => $data,
            'semester' => $semester_aktif
        ]);
    }

    public function pengisian_krs_data(Request $request)
    {
        $searchValue = $request->input('search.value');

        $angkatanAktif = date('Y') - 7;
        $arrayTahun = range($angkatanAktif, date('Y'));


        $semester_aktif = SemesterAktif::first();
        $query = ProgramStudi::with('fakultas')
                ->select('program_studis.*')
                ->selectRaw('CONCAT(program_studis.nama_jenjang_pendidikan, " - ", program_studis.nama_program_studi) AS nama_prodi')
                // ->selectRaw('(SELECT COUNT(DISTINCT peserta_kelas_kuliahs.id_registrasi_mahasiswa)
                //             FROM peserta_kelas_kuliahs
                //             JOIN kelas_kuliahs ON peserta_kelas_kuliahs.id_kelas_kuliah = kelas_kuliahs.id_kelas_kuliah
                //             WHERE peserta_kelas_kuliahs.id_prodi = program_studis.id_prodi
                //             AND kelas_kuliahs.id_semester = ?) AS jumlah_mahasiswa_isi_krs', [$semester_aktif->id_semester])
                // ->selectRaw('(SELECT COUNT(DISTINCT peserta_kelas_kuliahs.id_registrasi_mahasiswa)
                //             FROM peserta_kelas_kuliahs
                //             JOIN kelas_kuliahs ON peserta_kelas_kuliahs.id_kelas_kuliah = kelas_kuliahs.id_kelas_kuliah
                //             WHERE peserta_kelas_kuliahs.id_prodi = program_studis.id_prodi
                //             AND kelas_kuliahs.id_semester = ?
                //             AND peserta_kelas_kuliahs.approved = "1") AS jumlah_mahasiswa_approved', [$semester_aktif->id_semester])
                // ->selectRaw('(SELECT COUNT(DISTINCT peserta_kelas_kuliahs.id_registrasi_mahasiswa)
                //             FROM peserta_kelas_kuliahs
                //             JOIN kelas_kuliahs ON peserta_kelas_kuliahs.id_kelas_kuliah = kelas_kuliahs.id_kelas_kuliah
                //             WHERE peserta_kelas_kuliahs.id_prodi = program_studis.id_prodi
                //             AND kelas_kuliahs.id_semester = ?
                //             AND peserta_kelas_kuliahs.approved = "0") AS jumlah_mahasiswa_not_approved', [$semester_aktif->id_semester])
                    ->withCount([
                        'mahasiswa as jumlah_mahasiswa' => function ($query) {
                            $query->whereNull('id_jenis_keluar');
                        },
                        'mahasiswa as jumlah_mahasiswa_now' => function ($query) use ($arrayTahun) {
                            $query->whereNull('id_jenis_keluar')
                                ->whereIn(DB::raw('LEFT(id_periode_masuk, 4)'), $arrayTahun);
                        },
                        'peserta_kelas as jumlah_mahasiswa_isi_krs' => function ($query) use ($semester_aktif) {
                            $query->join('kelas_kuliahs as kk1', 'peserta_kelas_kuliahs.id_kelas_kuliah', '=', 'kk1.id_kelas_kuliah')
                                ->where('kk1.id_semester', $semester_aktif->id_semester)
                                ->distinct()
                                ->select(DB::raw('COUNT(DISTINCT peserta_kelas_kuliahs.id_registrasi_mahasiswa)'));
                        },
                        'peserta_kelas as jumlah_mahasiswa_approved' => function ($query) use ($semester_aktif) {
                            $query->join('kelas_kuliahs as kk2', 'peserta_kelas_kuliahs.id_kelas_kuliah', '=', 'kk2.id_kelas_kuliah')
                                ->where('kk2.id_semester', $semester_aktif->id_semester)
                                ->where('peserta_kelas_kuliahs.approved', '1')
                                ->distinct()
                                ->select(DB::raw('COUNT(DISTINCT peserta_kelas_kuliahs.id_registrasi_mahasiswa)'));
                        },
                        'peserta_kelas as jumlah_mahasiswa_not_approved' => function ($query) use ($semester_aktif) {
                            $query->join('kelas_kuliahs as kk3', 'peserta_kelas_kuliahs.id_kelas_kuliah', '=', 'kk3.id_kelas_kuliah')
                                ->where('kk3.id_semester', $semester_aktif->id_semester)
                                ->where('peserta_kelas_kuliahs.approved', '0')
                                ->distinct()
                                ->select(DB::raw('COUNT(DISTINCT peserta_kelas_kuliahs.id_registrasi_mahasiswa)'));
                        }
                    ])
                ->where('program_studis.status', 'A')
                ->orderBy('fakultas_id')
                ->orderBy('kode_program_studi', 'ASC');


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
}
