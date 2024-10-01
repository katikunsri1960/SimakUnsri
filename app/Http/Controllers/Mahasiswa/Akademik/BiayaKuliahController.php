<?php

namespace App\Http\Controllers\Mahasiswa\Akademik;

use Carbon\Carbon;
use App\Models\Semester;
use Illuminate\Http\Request;
use App\Models\SemesterAktif;
use App\Models\BeasiswaMahasiswa;
use App\Models\Connection\Tagihan;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Connection\Pembayaran;
use App\Models\Connection\Registrasi;
use App\Models\Mahasiswa\RiwayatPendidikan;

class BiayaKuliahController extends Controller
{
    public function index()
    {
        $semester_aktif = SemesterAktif::first();

        $user = auth()->user();

        $nim = RiwayatPendidikan::with('pembimbing_akademik')
                    ->select('riwayat_pendidikans.*')
                    ->where('id_registrasi_mahasiswa', $user->fk_id)
                    ->pluck('nim')
                    ->first();
                    
        $id_test = Registrasi::where('rm_nim', $user->username)->pluck('rm_no_test')->first();


        // dd($id_test);
        $beasiswa = BeasiswaMahasiswa::where('id_registrasi_mahasiswa', $user->fk_id)->first();
        // dd($beasiswa);

        $tagihan = Tagihan::with('pembayaran')
                ->whereIn('tagihan.nomor_pembayaran', [$id_test, $nim])
                ->where('tagihan.kode_periode', $semester_aktif->id_semester)
                ->first();

        if ($tagihan) {
            $tagihan->waktu_berakhir = Carbon::parse($tagihan->waktu_berakhir)->translatedFormat('d F Y');
        }

        $pembayaran = Tagihan::with('pembayaran')
            ->whereIn('nomor_pembayaran', [$id_test, $nim])
            ->orderBy('kode_periode', 'ASC')
            ->get();

        foreach ($pembayaran as $item) {
            if ($item->pembayaran) {
                $item->pembayaran->waktu_transaksi = Carbon::parse($item->pembayaran->waktu_transaksi)->translatedFormat('d F Y');
            }
        }

        // dd($tagihan);
        
        return view('mahasiswa.biaya-kuliah.index', ['tagihan' => $tagihan, 'pembayaran'=> $pembayaran, 'beasiswa'=> $beasiswa]);
    }
}
