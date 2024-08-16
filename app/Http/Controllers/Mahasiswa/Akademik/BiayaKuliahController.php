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

class BiayaKuliahController extends Controller
{
    public function index()
    {
        $semester_aktif = SemesterAktif::first();
        $user = auth()->user();
        $id_test = Registrasi::where('rm_nim', $user->username)->pluck('rm_no_test');

        // dd($id_test);
        $beasiswa = BeasiswaMahasiswa::where('id_registrasi_mahasiswa', $user->fk_id)->first();
        // dd($beasiswa);

        $tagihan = Tagihan::with('pembayaran')
                ->whereIn('tagihan.nomor_pembayaran', [$id_test, $user->username])
                ->where('tagihan.kode_periode', $semester_aktif->id_semester)
                ->select(
                    'tagihan.id_record_tagihan',
                    'tagihan.nama',
                    'tagihan.nomor_pembayaran',
                    'tagihan.total_nilai_tagihan',
                    'tagihan.kode_periode',
                    'tagihan.waktu_berlaku',
                    'tagihan.waktu_berakhir'
                )
                ->first();

        if ($tagihan) {
            $tagihan->waktu_berakhir = Carbon::parse($tagihan->waktu_berakhir)->translatedFormat('d F Y');
        }

        $pembayaran = Tagihan::with('pembayaran')
            ->whereIn('nomor_pembayaran', [$user->username, $id_test])
            ->select(
                'tagihan.id_record_tagihan',
                'tagihan.nomor_pembayaran',
                'tagihan.total_nilai_tagihan',
                'tagihan.kode_periode'
            )
            ->orderBy('kode_periode', 'ASC')
            ->get();

        foreach ($pembayaran as $item) {
            if ($item->pembayaran) {
                $item->pembayaran->waktu_transaksi = Carbon::parse($item->pembayaran->waktu_transaksi)->translatedFormat('d F Y');
            }
        }

        // dd($tagihan->pembayaran);
        
        return view('mahasiswa.biaya-kuliah.index', ['tagihan' => $tagihan, 'pembayaran'=> $pembayaran, 'beasiswa'=> $beasiswa]);
    }
}
