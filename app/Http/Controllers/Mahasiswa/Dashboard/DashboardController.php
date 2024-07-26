<?php

namespace App\Http\Controllers\Mahasiswa\Dashboard;

use Illuminate\Http\Request;
use App\Models\SemesterAktif;
use Illuminate\Support\Facades\DB;
use App\Models\Mahasiswa\Dashboard;
use App\Http\Controllers\Controller;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Perkuliahan\TranskripMahasiswa;
use App\Models\Perkuliahan\AktivitasKuliahMahasiswa;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        $riwayat_pendidikan = RiwayatPendidikan::where('id_registrasi_mahasiswa', $user->fk_id)
                            ->first();
                            // dd($riwayat_pendidikan);
        $prodi_id = $riwayat_pendidikan->id_prodi;
        

        $semester_aktif = SemesterAktif::leftJoin('semesters','semesters.id_semester','semester_aktifs.id_semester')
                        ->first();
                        // dd($semester_aktif);
        
        

        $smt = AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa', $user->fk_id)
                ->whereRaw("RIGHT(id_semester, 1) != 3")
                ->orderBy('id_semester', 'ASC')
                ->get();
                // dd($smt);
        
        $semester_ke = AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa', $user->fk_id)
                ->whereRaw("RIGHT(id_semester, 1) != 3")
                ->count();

        $tagihan = DB::connection('keu_con')
                ->table('tagihan')
                ->leftJoin('pembayaran', 'tagihan.id_record_tagihan', '=', 'pembayaran.id_record_tagihan')
                ->where('tagihan.nomor_pembayaran', $user->username)
                ->where('tagihan.kode_periode', $semester_aktif->id_semester)
                ->select(
                    'tagihan.nama',
                    'tagihan.nomor_pembayaran',
                    'tagihan.total_nilai_tagihan',
                    'tagihan.kode_periode',
                    // 'tagihan.nama_periode',
                    'tagihan.waktu_berlaku',
                    'tagihan.waktu_berakhir',
                    'pembayaran.status_pembayaran'
                )
                ->first();
                // dd($tagihan);

        $transkrip = TranskripMahasiswa::select(
                DB::raw('SUM(CAST(sks_mata_kuliah AS UNSIGNED)) as total_sks'), // Mengambil total SKS tanpa nilai desimal
                DB::raw('ROUND(SUM(nilai_indeks * sks_mata_kuliah) / SUM(sks_mata_kuliah), 2) as ipk') // Mengambil IPK dengan 2 angka di belakang koma
                )
                ->where('id_registrasi_mahasiswa', $user->fk_id)
                ->whereNotIn('nilai_huruf', ['F', ''])
                ->groupBy('id_registrasi_mahasiswa')
                ->first();
                // dd($transkrip);

        return view('mahasiswa.dashboard', compact(
            'riwayat_pendidikan',
            'semester_aktif',
            'semester_ke', 'smt', 'tagihan','transkrip'
        ));
    }
}
