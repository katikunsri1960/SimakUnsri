<?php

namespace App\Http\Controllers\Mahasiswa\Akademik;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\SemesterAktif;
use App\Models\Connection\Tagihan;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Semester;

class BiayaKuliahController extends Controller
{
    public function index()
    {
        $semester_aktif = SemesterAktif::first();
        $nim = auth()->user()->username;

        $tagihan = DB::connection('keu_con')
            ->table('tagihan')
            ->leftJoin('pembayaran', 'tagihan.id_record_tagihan', '=', 'pembayaran.id_record_tagihan')
            ->where('tagihan.nomor_pembayaran', $nim)
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

        if ($tagihan) {
            // Format tanggal ke dalam format Indonesia
            $tagihan->waktu_berakhir = Carbon::parse($tagihan->waktu_berakhir)->translatedFormat('d F Y');
        }

        $semester_tagihan = Semester::where('id_semester', $tagihan->kode_periode)->first();


        $pembayaran = DB::connection('keu_con')
            ->table('pembayaran')
            ->where('nomor_pembayaran', $nim)
            ->select(
                'pembayaran.nomor_pembayaran',
                'pembayaran.id_record_tagihan',
                'pembayaran.waktu_transaksi',
                'pembayaran.total_nilai_pembayaran',
                'pembayaran.status_pembayaran',
                'pembayaran.kode_bank',
                'pembayaran.kanal_bayar_bank'
            )
            ->get(); // Menggunakan get() untuk mengambil semua pembayaran

        // Format tanggal pembayaran jika ada pembayaran
        foreach ($pembayaran as $item) {
            $item->waktu_transaksi = Carbon::parse($item->waktu_transaksi)->translatedFormat('d F Y');
        }
        // dd($pembayaran->waktu_transaksi);

        // $semester_pembayaran = Semester::where('id_semester', $pembayaran->kode_periode)->first();

        return view('mahasiswa.biaya-kuliah.index', ['tagihan' => $tagihan, 'semester_tagihan'=> $semester_tagihan, 'pembayaran'=> $pembayaran]);
    }
}
