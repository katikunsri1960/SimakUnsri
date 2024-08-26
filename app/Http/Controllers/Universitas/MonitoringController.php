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

        $data = DB::table('riwayat_pendidikans as rp')
            ->join('peserta_kelas_kuliahs as pk', 'rp.id_registrasi_mahasiswa', '=', 'pk.id_registrasi_mahasiswa')
            ->join('kelas_kuliahs as kk', 'pk.id_kelas_kuliah', '=', 'kk.id_kelas_kuliah')
            ->select(
                'rp.id_prodi','rp.nama_program_studi',
                DB::raw('COUNT(DISTINCT CASE WHEN pk.approved = 1 THEN rp.id_registrasi_mahasiswa END) as jumlah_mahasiswa_approved'),
                DB::raw('COUNT(DISTINCT CASE WHEN pk.approved = 0 THEN rp.id_registrasi_mahasiswa END) as jumlah_mahasiswa_not_approved')
            )
            ->where('kk.id_semester', $semester_aktif->id_semester)
            ->groupBy('rp.id_prodi','rp.nama_program_studi')
            ->orderBy('rp.nama_program_studi')
            ->get();

        return view('universitas.monitoring.pengisian-krs.index', [
            'data' => $data,
            'semester' => $semester_aktif
        ]);
    }
}
