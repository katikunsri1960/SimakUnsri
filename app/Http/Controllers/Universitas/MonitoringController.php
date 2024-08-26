<?php

namespace App\Http\Controllers\Universitas;

use App\Http\Controllers\Controller;
use App\Models\SemesterAktif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MonitoringController extends Controller
{
    public function pengisian_krs()
    {
        $semester_aktif = SemesterAktif::first();

        $data = DB::table('program_studis')->select(
                            'fakultas.id',
                            'fakultas.nama_fakultas',
                            'program_studis.id_prodi',
                            DB::raw('CONCAT(program_studis.nama_jenjang_pendidikan, " - ", program_studis.nama_program_studi) AS nama_prodi'),
                            DB::raw('(SELECT COUNT(riwayat_pendidikans.id_registrasi_mahasiswa) 
                                    FROM riwayat_pendidikans 
                                    WHERE riwayat_pendidikans.id_prodi = program_studis.id_prodi 
                                    AND riwayat_pendidikans.id_jenis_keluar IS NULL) AS jumlah_mahasiswa'),
                            DB::raw('(SELECT COUNT(DISTINCT peserta_kelas_kuliahs.id_registrasi_mahasiswa) 
                                    FROM peserta_kelas_kuliahs
                                    JOIN kelas_kuliahs ON peserta_kelas_kuliahs.id_kelas_kuliah = kelas_kuliahs.id_kelas_kuliah
                                    WHERE peserta_kelas_kuliahs.id_prodi = program_studis.id_prodi 
                                    AND kelas_kuliahs.id_semester = "'.$semester_aktif->id_semester.'") AS jumlah_mahasiswa_isi_krs'),
                            DB::raw('(SELECT COUNT(DISTINCT peserta_kelas_kuliahs.id_registrasi_mahasiswa) 
                                    FROM peserta_kelas_kuliahs
                                    JOIN kelas_kuliahs ON peserta_kelas_kuliahs.id_kelas_kuliah = kelas_kuliahs.id_kelas_kuliah
                                    WHERE peserta_kelas_kuliahs.id_prodi = program_studis.id_prodi 
                                    AND kelas_kuliahs.id_semester = "'.$semester_aktif->id_semester.'"
                                    AND peserta_kelas_kuliahs.approved = "1") AS jumlah_mahasiswa_approved'),
                            DB::raw('(SELECT COUNT(DISTINCT peserta_kelas_kuliahs.id_registrasi_mahasiswa) 
                                    FROM peserta_kelas_kuliahs
                                    JOIN kelas_kuliahs ON peserta_kelas_kuliahs.id_kelas_kuliah = kelas_kuliahs.id_kelas_kuliah
                                    WHERE peserta_kelas_kuliahs.id_prodi = program_studis.id_prodi 
                                    AND kelas_kuliahs.id_semester = "'.$semester_aktif->id_semester.'" 
                                    AND peserta_kelas_kuliahs.approved = "0") AS jumlah_mahasiswa_not_approved')
                        )
                        ->join('fakultas', 'fakultas.id', '=', 'program_studis.fakultas_id')
                        ->where('program_studis.status', 'A')
                        ->orderBy('fakultas.id')
                        ->orderBy('nama_prodi', 'ASC')
                        ->get();
        // dd($data);

        return view('universitas.monitoring.pengisian-krs.index', [
            'data' => $data,
            'semester' => $semester_aktif
        ]);
    }
}
