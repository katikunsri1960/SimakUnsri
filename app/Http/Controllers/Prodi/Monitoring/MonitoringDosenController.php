<?php

namespace App\Http\Controllers\Prodi\Monitoring;

use App\Http\Controllers\Controller;
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

        $data = $query->get();

        return view('prodi.monitoring.pengisian-krs.index', [
            'data' => $data
        ]);

    }
}
