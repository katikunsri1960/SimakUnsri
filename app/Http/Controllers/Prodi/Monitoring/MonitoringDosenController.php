<?php

namespace App\Http\Controllers\Prodi\Monitoring;

use App\Http\Controllers\Controller;
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
                        ->where('program_studis.id_prodi', auth()->user()->fk_id);
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

                // $query->addSelect(DB::raw("
                // (CASE
                //     WHEN EXISTS (
                //         SELECT 1
                //             FROM peserta_kelas_kuliahs
                //             JOIN riwayat_pendidikans ON peserta_kelas_kuliahs.id_registrasi_mahasiswa = riwayat_pendidikans.id_registrasi_mahasiswa
                //             JOIN kelas_kuliahs ON peserta_kelas_kuliahs.id_kelas_kuliah = kelas_kuliahs.id_kelas_kuliah
                //             WHERE kelas_kuliahs.id_semester = ".$semesterAktif."
                //             AND riwayat_pendidikans.id_prodi = program_studis.id_prodi
                //     ) THEN (
                //         SELECT COUNT(DISTINCT peserta_kelas_kuliahs.id_registrasi_mahasiswa)
                //             FROM peserta_kelas_kuliahs
                //             JOIN kelas_kuliahs ON peserta_kelas_kuliahs.id_kelas_kuliah = kelas_kuliahs.id_kelas_kuliah
                //             WHERE kelas_kuliahs.id_semester = ".$semesterAktif."
                //             AND peserta_kelas_kuliahs.id_prodi = program_studis.id_prodi
                //     )
                //     ELSE (
                //         SELECT COUNT(DISTINCT anggota_aktivitas_mahasiswas.id_registrasi_mahasiswa)
                //         FROM anggota_aktivitas_mahasiswas
                //         JOIN aktivitas_mahasiswas ON anggota_aktivitas_mahasiswas.id_aktivitas = aktivitas_mahasiswas.id_aktivitas
                //         WHERE aktivitas_mahasiswas.id_semester = ".$semesterAktif."
                //         AND aktivitas_mahasiswas.id_jenis_aktivitas IN (1,2,3,4,5,6,13,14,15,16,17,18,19,20,21,22)
                //         AND aktivitas_mahasiswas.id_prodi = program_studis.id_prodi
                //     )
                // END) AS jumlah_mahasiswa_isi_krs
                // "));

                // $query->addSelect(DB::raw("
                //         (CASE
                //             WHEN EXISTS (
                //                 SELECT 1 FROM peserta_kelas_kuliahs,riwayat_pendidikans,kelas_kuliahs
                //                 WHERE peserta_kelas_kuliahs.id_registrasi_mahasiswa = riwayat_pendidikans.id_registrasi_mahasiswa
                //                 AND peserta_kelas_kuliahs.id_kelas_kuliah=kelas_kuliahs.id_kelas_kuliah
                //                 AND kelas_kuliahs.id_semester= ".$semesterAktif."
                //                 AND peserta_kelas_kuliahs.approved = '1'
                //             ) THEN (
                //                 SELECT COUNT(DISTINCT peserta_kelas_kuliahs.id_registrasi_mahasiswa)
                //                 FROM peserta_kelas_kuliahs, kelas_kuliahs
                //                 WHERE peserta_kelas_kuliahs.id_kelas_kuliah = kelas_kuliahs.id_kelas_kuliah
                //                 AND peserta_kelas_kuliahs.id_prodi = program_studis.id_prodi
                //                 AND kelas_kuliahs.id_semester = ".$semesterAktif."
                //                 AND peserta_kelas_kuliahs.approved = '1'
                //             )
                //             ELSE (
                //                 SELECT COUNT(DISTINCT anggota_aktivitas_mahasiswas.id_registrasi_mahasiswa)
                //                 FROM anggota_aktivitas_mahasiswas, aktivitas_mahasiswas
                //                 WHERE anggota_aktivitas_mahasiswas.id_aktivitas = aktivitas_mahasiswas.id_aktivitas
                //                 AND aktivitas_mahasiswas.id_semester = ".$semesterAktif."
                //                 AND aktivitas_mahasiswas.approve_krs = '1'
                //                 AND aktivitas_mahasiswas.id_jenis_aktivitas IN (1,2,3,4,5,6,13,14,15,16,17,18,19,20,21,22)
                //             )
                //         END) AS jumlah_mahasiswa_approved
                //         "));

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

        $data = $query->get();

        $isi_krs = RiwayatPendidikan::with('dosen_pa')->where('id_prodi', auth()->user()->fk_id)
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

            $approve = RiwayatPendidikan::with('dosen_pa')->where('id_prodi', auth()->user()->fk_id)
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


            $non_approve = RiwayatPendidikan::with('dosen_pa')->where('id_prodi', auth()->user()->fk_id)
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
        return view('prodi.monitoring.pengisian-krs.index', [
            'data' => $data,
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
            $data
        ]);
    }

    public function detail_isi_krs()
    {
        $prodi = ProgramStudi::where('id_prodi', auth()->user()->fk_id)->first();
        $id_prodi = $prodi->id_prodi;
        $semesterAktif = SemesterAktif::first()->id_semester;

        $data = RiwayatPendidikan::with('dosen_pa')->where('id_prodi', $id_prodi)
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

        $data = RiwayatPendidikan::with('dosen_pa')->where('id_prodi', $id_prodi)
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

        $data = RiwayatPendidikan::with('dosen_pa')->where('id_prodi', $id_prodi)
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
}
