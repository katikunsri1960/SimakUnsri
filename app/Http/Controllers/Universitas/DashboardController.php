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
        // $kelasKuliahIds = DB::table('kelas_kuliahs')
        // ->where('id_semester', 20241)
        // ->pluck('id_kelas_kuliah');

        // $data = RiwayatPendidikan::select(
        //             DB::raw('LEFT(id_periode_masuk, 4) as angkatan'),
        //             'riwayat_pendidikans.id_prodi',
        //             DB::raw('COUNT(*) as jumlah_mahasiswa')
        //         )->with(['peserta_kelas.kelas_kuliah' => function($q) use ($kelasKuliahIds) {
        //             $q->whereIn('id_kelas_kuliah', $kelasKuliahIds);
        //         }])

        //         ->whereNotIn('riwayat_pendidikans.id_jenis_keluar', $jenisKeluar)
        //         ->groupBy(DB::raw('LEFT(id_periode_masuk, 4)'), 'id_prodi')
        //         ->get();
        // dd($data);
        return view('universitas.dashboard');
    }
}
