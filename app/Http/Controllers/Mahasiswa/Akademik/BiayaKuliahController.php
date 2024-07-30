<?php

namespace App\Http\Controllers\Mahasiswa\Akademik;

use Carbon\Carbon;
use App\Models\Semester;
use Illuminate\Http\Request;
use App\Models\SemesterAktif;
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


        $tagihan = Tagihan::with('pembayaran')
                ->where('tagihan.nomor_pembayaran', $user->username)
                ->where('tagihan.kode_periode', $semester_aktif->id_semester)
                ->select(
                    'tagihan.id_record_tagihan',
                    'tagihan.nama',
                    'tagihan.nomor_pembayaran',
                    'tagihan.total_nilai_tagihan',
                    'tagihan.kode_periode',
                    // 'tagihan.nama_periode',
                    'tagihan.waktu_berlaku',
                    'tagihan.waktu_berakhir'
                )
                ->first();
            // dd($tagihan);

        if ($tagihan) {
            // Format tanggal ke dalam format Indonesia
            $tagihan->waktu_berakhir = Carbon::parse($tagihan->waktu_berakhir)->translatedFormat('d F Y');
        }

        // $semester_tagihan = Semester::where('id_semester', $tagihan->kode_periode)->first();

        $pembayaran = Tagihan::with('pembayaran')
            ->whereIn('nomor_pembayaran', [$user->username, $id_test])
            ->select(
                'tagihan.id_record_tagihan',
                'tagihan.nomor_pembayaran',
                'tagihan.total_nilai_tagihan',
                'tagihan.kode_periode'
            )
            ->orderBy('kode_periode', 'ASC')
            ->get(); // Menggunakan get() untuk mengambil semua pembayaran
        // dd($pembayaran);

        // Format tanggal pembayaran jika ada pembayaran
        foreach ($pembayaran as $item) {
            $item->pembayaran->waktu_transaksi = Carbon::parse($item->pembayaran->waktu_transaksi)->translatedFormat('d F Y');
        }
        
        return view('mahasiswa.biaya-kuliah.index', ['tagihan' => $tagihan, 'pembayaran'=> $pembayaran]);
    }
}
