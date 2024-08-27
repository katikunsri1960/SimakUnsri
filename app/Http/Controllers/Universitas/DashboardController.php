<?php

namespace App\Http\Controllers\Universitas;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa\RiwayatPendidikan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // $jenisKeluar = ['0', '1', '2', '3', '4', '5', '6', '7'];
        // // $kelasKuliahIds = DB::table('kelas_kuliahs')
        // //                 ->where('id_semester', 20241)
        // //                 ->pluck('id_kelas_kuliah');

        // $data = RiwayatPendidikan::select(
        //                     DB::raw('LEFT(id_periode_masuk, 4) as angkatan'),
        //                     'riwayat_pendidikans.id_prodi',
        //                     DB::raw('COUNT(DISTINCT CASE WHEN peserta_kelas_kuliahs.approved = 1 THEN riwayat_pendidikans.id_registrasi_mahasiswa END) as jumlah_approved'),
        //                     DB::raw('COUNT(DISTINCT CASE WHEN peserta_kelas_kuliahs.approved = 0 THEN riwayat_pendidikans.id_registrasi_mahasiswa END) as jumlah_not_approved')
        //                 )
        //                 ->join('peserta_kelas_kuliahs', 'riwayat_pendidikans.id_registrasi_mahasiswa', '=', 'peserta_kelas_kuliahs.id_registrasi_mahasiswa')
        //                 ->join('kelas_kuliahs', 'peserta_kelas_kuliahs.id_kelas_kuliah', '=', 'kelas_kuliahs.id_kelas_kuliah')
        //                 ->where('kelas_kuliahs.id_semester', 20241)
        //                 ->whereNotIn('riwayat_pendidikans.id_jenis_keluar', $jenisKeluar)
        //                 ->groupBy(DB::raw('LEFT(id_periode_masuk, 4)'), 'riwayat_pendidikans.id_prodi')
        //                 ->get();
        // dd($data);
        return view('universitas.dashboard');
    }

    public function generateApproveKrs()
    {

        $jenisKeluar = ['0', '1', '2', '3', '4', '5', '6', '7'];

        $data = RiwayatPendidikan::select(
                    'riwayat_pendidikans.id_prodi',
                    DB::raw('COUNT(DISTINCT CASE WHEN peserta_kelas_kuliahs.approved = 1 THEN riwayat_pendidikans.id_registrasi_mahasiswa END) as jumlah_mahasiswa_approved'),
                    DB::raw('COUNT(DISTINCT CASE WHEN peserta_kelas_kuliahs.approved = 0 THEN riwayat_pendidikans.id_registrasi_mahasiswa END) as jumlah_mahasiswa_not_approved')
                )
                ->join('peserta_kelas_kuliahs', 'riwayat_pendidikans.id_registrasi_mahasiswa', '=', 'peserta_kelas_kuliahs.id_registrasi_mahasiswa')
                ->join('kelas_kuliahs', 'peserta_kelas_kuliahs.id_kelas_kuliah', '=', 'kelas_kuliahs.id_kelas_kuliah')
                ->where('kelas_kuliahs.id_semester', 20241)
                ->whereNotIn('riwayat_pendidikans.id_jenis_keluar', $jenisKeluar)
                ->groupBy('riwayat_pendidikans.id_prodi')
                ->get();
        return response()->json($data);
    }
}
