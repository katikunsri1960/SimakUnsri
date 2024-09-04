<?php

namespace App\Http\Controllers\Prodi\Monitoring;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa\LulusDo;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\ProgramStudi;
use App\Models\SemesterAktif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MonitoringDosenController extends Controller
{
    public function monitoring_nilai()
    {
        return view('prodi.monitoring.entry-nilai.index');
    }

    public function monitoring_pengajaran()
    {
        return view('prodi.monitoring.pengajaran-dosen.index');
    }

    public function pengisian_krs()
    {
        return view('prodi.monitoring.pengisian-krs.index');
    }

    public function pengisian_krs_data()
    {
        $angkatanAktif = date('Y') - 7;
        $arrayTahun = range($angkatanAktif, date('Y'));
        $semesterAktif = SemesterAktif::first()->id_semester;

        $db = new RiwayatPendidikan();

        $baseQuery = $db->where('id_prodi', auth()->user()->fk_id)
                ->whereNull('id_jenis_keluar');

        // Get the total count of students
        $jumlah_mahasiswa = (clone $baseQuery)->count();

        // Get the count of students for the current period
        $jumlah_mahasiswa_now = (clone $baseQuery)
                                ->whereIn(DB::raw('LEFT(id_periode_masuk, 4)'), $arrayTahun)
                                ->count();

        $isi_krs = $db->where('id_prodi', auth()->user()->fk_id)
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

            $approve = $db->where('id_prodi', auth()->user()->fk_id)
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


            $non_approve = $db->where('id_prodi', auth()->user()->fk_id)
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

            return response()->json([
                'jumlah_mahasiswa' => $jumlah_mahasiswa,
                'jumlah_mahasiswa_now' => $jumlah_mahasiswa_now,
                'isi_krs' => $isi_krs,
                'approve' => $approve,
                'non_approve' => $non_approve
            ]);
    }


    public function mahasiswa_aktif()
    {
        $prodi = ProgramStudi::where('id_prodi', auth()->user()->fk_id)->first();
        $id_prodi = $prodi->id_prodi;

        $data = RiwayatPendidikan::where('id_prodi', $id_prodi)
                ->whereNull('id_jenis_keluar')
                ->orderBy('id_periode_masuk', 'ASC')
                ->get();

        return view('prodi.monitoring.pengisian-krs.mahasiswa-aktif', [
            'prodi' => $prodi,
            'data' => $data
        ]);
    }

    public function mahasiswa_aktif_min_tujuh()
    {
        $prodi = ProgramStudi::where('id_prodi', auth()->user()->fk_id)->first();
        $id_prodi = $prodi->id_prodi;

        $angkatanAktif = date('Y') - 7;
        $arrayTahun = range($angkatanAktif, date('Y'));

        $data = RiwayatPendidikan::where('id_prodi', $id_prodi)
                ->whereNull('id_jenis_keluar')
                ->whereIn(DB::raw('LEFT(id_periode_masuk, 4)'), $arrayTahun)
                ->orderBy('id_periode_masuk', 'ASC')
                ->get();

        return view('prodi.monitoring.pengisian-krs.mahasiswa-aktif-min-tujuh', [
            'data' => $data,
            'prodi' => $prodi
        ]);
    }

    public function detail_isi_krs()
    {
        $prodi = ProgramStudi::where('id_prodi', auth()->user()->fk_id)->first();
        $id_prodi = $prodi->id_prodi;
        $semesterAktif = SemesterAktif::first()->id_semester;

        $data = RiwayatPendidikan::with('pembimbing_akademik')->where('id_prodi', $id_prodi)
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
                ->get();

        return view('prodi.monitoring.pengisian-krs.detail-isi-krs', [
            'prodi' => $prodi,
            'data' => $data
        ]);
    }

    public function approve_krs()
    {
        $prodi = ProgramStudi::where('id_prodi', auth()->user()->fk_id)->first();
        $id_prodi = $prodi->id_prodi;
        $semesterAktif = SemesterAktif::first()->id_semester;

        $data = RiwayatPendidikan::with('pembimbing_akademik')->where('id_prodi', $id_prodi)
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
                ->get();

        return view('prodi.monitoring.pengisian-krs.approve-krs', [
            'prodi' => $prodi,
            'data' => $data
        ]);
    }

    public function non_approve_krs()
    {
        $prodi = ProgramStudi::where('id_prodi', auth()->user()->fk_id)->first();
        $id_prodi = $prodi->id_prodi;
        $semesterAktif = SemesterAktif::first()->id_semester;

        $data = RiwayatPendidikan::with('pembimbing_akademik')->where('id_prodi', $id_prodi)
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
                ->get();

        return view('prodi.monitoring.pengisian-krs.non-approve-krs', [
            'prodi' => $prodi,
            'data' => $data
        ]);
    }

    public function tidak_isi_krs()
    {
        $prodi = ProgramStudi::where('id_prodi', auth()->user()->fk_id)->first();
        $id_prodi = $prodi->id_prodi;
        $semesterAktif = SemesterAktif::first()->id_semester;

        $db = new RiwayatPendidikan();
        $data = $db->tidak_isi_krs($id_prodi, $semesterAktif);

        return view('prodi.monitoring.pengisian-krs.tidak-isi-krs', [
            'prodi' => $prodi,
            'data' => $data
        ]);
    }

    public function lulus_do(Request $request)
    {
        $db = new LulusDo();
        $jenis_keluar = $db->select('id_jenis_keluar', 'nama_jenis_keluar')->distinct()->get();

        $jenis_keluar_counts = $db->select('id_jenis_keluar','nama_jenis_keluar', DB::raw('count(*) as total'))
                                ->where('id_prodi', auth()->user()->fk_id)
                                ->groupBy('id_jenis_keluar','nama_jenis_keluar');


        if ($request->has('angkatan') && !empty($request->angkatan)) {
            $filter = $request->angkatan;
            $jenis_keluar_counts->whereIn('angkatan', $filter);
        }

        if($request->has('jenis_keluar') && !empty($request->jenis_keluar)) {
            $filter = $request->jenis_keluar;
            $jenis_keluar_counts->whereIn('id_jenis_keluar', $filter);
        }

        $jenis_keluar_counts = $jenis_keluar_counts->get();

        $angkatan = $db->select('angkatan')->distinct()->orderBy('angkatan', 'desc')->get();

        return view('prodi.monitoring.kelulusan.index', [
            'jenis_keluar' => $jenis_keluar,
            'jenis_keluar_counts' => $jenis_keluar_counts,
            'angkatan' => $angkatan
        ]);
    }

    public function lulus_do_data(Request $request)
    {
        $searchValue = $request->input('search.value');

        $query = LulusDo::with('prodi', 'biodata')->where('id_prodi', auth()->user()->fk_id);

        if ($searchValue) {
            $query = $query->where('nim', 'like', '%' . $searchValue . '%')
                ->orWhere('nama_mahasiswa', 'like', '%' . $searchValue . '%');
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
            $columns = ['nim','nama_mahasiswa', 'angkatan', 'tanggal_keluar', 'nm_smt', 'keterangan'];

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

        $recordsTotal = LulusDo::where('id_prodi', auth()->user()->fk_id)->count();

        $response = [
            'draw' => $request->input('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ];

        return response()->json($response);
    }
}
