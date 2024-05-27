<?php

namespace App\Http\Controllers\Prodi;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa\RiwayatPendidikan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // TODO : Jumlah mahasiswa Berdasarkan status Mahasiswa
        // distinct('keterangan_keluar') from riwayat_pendidikan

        $riwayat = RiwayatPendidikan::select('keterangan_keluar')->distinct()->get();
        
        // $status = RiwayatPendidikan::distinct('keterangan_keluar')->get();
        // dd($status);
        return view('prodi.dashboard');
    }
}
