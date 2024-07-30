<?php

namespace App\Http\Controllers\Mahasiswa\Akademik;

use App\Models\Fakultas;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use App\Models\SemesterAktif;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Perkuliahan\MataKuliah;
use App\Models\Mahasiswa\PengajuanCuti;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Perkuliahan\AktivitasMahasiswa;
use App\Models\Perkuliahan\TranskripMahasiswa;
use App\Models\Perkuliahan\AktivitasKuliahMahasiswa;
use App\Models\Perkuliahan\AnggotaAktivitasMahasiswa;

class AktivitasMagangController extends Controller
{
    public function index_magang(Request $request)
    {
        // DATA BAHAN
        if ($request->has('semester') && $request->semester != '') {
            $semester_select = $request->semester;
        } else {
            $semester_select = SemesterAktif::first()->id_semester;
        }
        // dd($semester_select);

        $id_reg = auth()->user()->fk_id;

        $riwayat_pendidikan = RiwayatPendidikan::select('riwayat_pendidikans.*', 'biodata_dosens.id_dosen', 'biodata_dosens.nama_dosen')
        ->where('id_registrasi_mahasiswa', $id_reg)
        ->leftJoin('biodata_dosens', 'biodata_dosens.id_dosen', '=', 'riwayat_pendidikans.dosen_pa')
        ->first();

        $prodi_id = $riwayat_pendidikan->id_prodi;
        
        $akm = AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa', $id_reg)
                    ->whereNotIn('id_status_mahasiswa', ['N'])
                    ->orderBy('id_semester', 'DESC')
                    ->first();
        
        $semester_aktif = SemesterAktif::leftJoin('semesters','semesters.id_semester','semester_aktifs.id_semester')
                    ->first();
                    
        $krs_akt = AnggotaAktivitasMahasiswa::select(
            'anggota_aktivitas_mahasiswas.*', 'aktivitas_mahasiswas.*', 'bimbing_mahasiswas.*'
        )
            ->leftJoin('aktivitas_mahasiswas', 'aktivitas_mahasiswas.id_aktivitas', '=', 'anggota_aktivitas_mahasiswas.id_aktivitas')
            ->leftJoin('bimbing_mahasiswas', 'bimbing_mahasiswas.id_aktivitas', '=', 'anggota_aktivitas_mahasiswas.id_aktivitas')
            ->where('anggota_aktivitas_mahasiswas.id_registrasi_mahasiswa', $id_reg)
            ->where('aktivitas_mahasiswas.id_semester', $semester_aktif->id_semester)
            ->where('aktivitas_mahasiswas.id_prodi', $prodi_id)
            ->whereIn('aktivitas_mahasiswas.id_jenis_aktivitas', ['2', '3', '4', '22'])
            ->whereNotNull('bimbing_mahasiswas.id_bimbing_mahasiswa')
            ->get();

        return view('mahasiswa.perkuliahan.ksm.aktivitas-magang.index',
        compact(
            'semester_select',
            'semester_aktif',
            'krs_akt'            
        ));
    }
}
