<?php

namespace App\Http\Controllers\Mahasiswa;

use Illuminate\Http\Request;
use App\Models\SemesterAktif;
use App\Models\Mahasiswa\Dashboard;
use App\Http\Controllers\Controller;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Perkuliahan\AktivitasKuliahMahasiswa;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $id_reg = auth()->user()->fk_id;

        $riwayat_pendidikan = RiwayatPendidikan::where('id_registrasi_mahasiswa', $id_reg)
                            ->first();
        
        $prodi_id = $riwayat_pendidikan->id_prodi;
        // dd($prodi_id);

        $semester_aktif = SemesterAktif::leftJoin('semesters','semesters.id_semester','semester_aktifs.id_semester')
                        ->first();
                        // dd($semester_aktif);
        
        $akm = AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa', $id_reg)
                ->whereRaw("RIGHT(id_semester, 1) != 3")
                ->orderBy('id_semester', 'DESC')
                ->first();

        $smt = AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa', $id_reg)
                ->whereRaw("RIGHT(id_semester, 1) != 3")
                ->orderBy('id_semester', 'ASC')
                ->get();
                // dd($smt);
        
        $semester_ke = AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa', $id_reg)
                ->whereRaw("RIGHT(id_semester, 1) != 3")
                ->count();

        return view('mahasiswa.dashboard', compact(
            'riwayat_pendidikan',
            'semester_aktif',
            'akm',
            'semester_ke', 'smt'
        ));
    }
}
