<?php

namespace App\Http\Controllers\Mahasiswa\Akademik;

use Carbon\Carbon;
use App\Models\Semester;
use Illuminate\Http\Request;
use App\Models\SemesterAktif;
use App\Models\BeasiswaMahasiswa;
use App\Models\Connection\Tagihan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
                    
        try {
            $id_test = Registrasi::where('rm_nim', $nim)
                        ->pluck('rm_no_test')
                        ->first();
        } catch (\Exception $e) {
            Log::error('Gagal mengambil rm_no_test dari Registrasi: ' . $e->getMessage());
            $id_test = null;
        }

        $nomorPembayaranList = array_filter([$id_test, $nim]);
        $beasiswa = BeasiswaMahasiswa::where('id_registrasi_mahasiswa', $user->fk_id)->first();

        // ----------------------------------------------------
        // Helper Function untuk Format Kode Periode (Semester)
        // ----------------------------------------------------
        $formatPeriode = function ($kodePeriode) {
            if (!$kodePeriode) return '-';
            $tahun = substr($kodePeriode, 0, 4);
            $semester = substr($kodePeriode, 4, 1);
            $strSemester = ($semester == '1') ? 'Ganjil' : (($semester == '2') ? 'Genap' : 'Pendek');
            return "{$tahun}/" . ($tahun + 1) . " {$strSemester}";
        };

        // ----------------------------------------------------
        // 1. Ambil Data Tagihan Semester Aktif ($tagihan)
        // ----------------------------------------------------
        try {
            $tagihan = Tagihan::with('pembayaran')
                ->whereIn('tagihan.nomor_pembayaran', $nomorPembayaranList)
                ->where('tagihan.kode_periode', $semester_aktif->id_semester)
                ->first();

        } catch (\Illuminate\Database\QueryException $e) {
            Log::warning('Koneksi keu_con terputus pada query Tagihan. Beralih ke import_tagihan.', [
                'error' => $e->getMessage()
            ]);

            $tagihanLocal = DB::table('import_tagihan')
                ->whereIn('nomor_pembayaran', $nomorPembayaranList)
                ->where('kode_periode', $semester_aktif->id_semester)
                ->first();

            if ($tagihanLocal) {
                $pembayaranLocal = DB::table('import_pembayaran')
                    ->where('nomor_pembayaran', $tagihanLocal->nomor_pembayaran)
                    ->first();

                $tagihanLocal->pembayaran = $pembayaranLocal;
                $tagihan = $tagihanLocal;
            } else {
                $tagihan = null;
            }
        }

        if ($tagihan) {
            // Pasang formatted_kode_periode jika belum ada
            if (!isset($tagihan->formatted_kode_periode)) {
                $tagihan->formatted_kode_periode = $formatPeriode($tagihan->kode_periode ?? null);
            }

            if (isset($tagihan->waktu_berakhir)) {
                $tagihan->waktu_berakhir = Carbon::parse($tagihan->waktu_berakhir)->translatedFormat('d F Y');
            }
        }

        // ----------------------------------------------------
        // 2. Ambil Riwayat Pembayaran Seluruh Semester ($pembayaran)
        // ----------------------------------------------------
        try {
            $pembayaran = Tagihan::with('pembayaran')
                ->whereIn('nomor_pembayaran', $nomorPembayaranList)
                ->orderBy('kode_periode', 'ASC')
                ->get();

        } catch (\Illuminate\Database\QueryException $e) {
            Log::warning('Koneksi keu_con terputus pada query Riwayat Pembayaran. Beralih ke import_tagihan.', [
                'error' => $e->getMessage()
            ]);

            $tagihanListLocal = DB::table('import_tagihan')
                ->whereIn('nomor_pembayaran', $nomorPembayaranList)
                ->orderBy('kode_periode', 'ASC')
                ->get();

            $noPembayaranArray = $tagihanListLocal->pluck('nomor_pembayaran')->filter()->toArray();

            $pembayaranListLocal = DB::table('import_pembayaran')
                ->whereIn('nomor_pembayaran', $noPembayaranArray)
                ->get()
                ->keyBy('nomor_pembayaran');

            foreach ($tagihanListLocal as $item) {
                $item->pembayaran = $pembayaranListLocal->get($item->nomor_pembayaran) ?? null;
            }

            $pembayaran = $tagihanListLocal;
        }

        // Penyiapan data tanggal dan formatted_kode_periode untuk daftar pembayaran
        foreach ($pembayaran as $item) {
            if (!isset($item->formatted_kode_periode)) {
                $item->formatted_kode_periode = $formatPeriode($item->kode_periode ?? null);
            }

            if (isset($item->pembayaran) && isset($item->pembayaran->waktu_transaksi)) {
                $item->pembayaran->waktu_transaksi = Carbon::parse($item->pembayaran->waktu_transaksi)->translatedFormat('d F Y');
            }
        }

        return view('mahasiswa.biaya-kuliah.index', [
            'tagihan' => $tagihan,
            'pembayaran' => $pembayaran,
            'beasiswa' => $beasiswa
        ]);
    }
}