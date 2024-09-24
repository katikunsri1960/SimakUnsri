<?php

namespace App\Http\Controllers\Fakultas\Akademik;

use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Perkuliahan\TranskripMahasiswa;
use App\Models\Perkuliahan\AktivitasKuliahMahasiswa;

class TranskripController extends Controller
{
    public function index()
    {
        $jobData =  DB::table('job_batches')->where('name', 'transkrip-mahasiswa')->where('pending_jobs', '>', 0)->first();

        $statusSync = $jobData ? 1 : 0;

        $id_batch = $jobData ? $jobData->id : null;
        
        return view('fakultas.data-akademik.transkrip.index', [
            'statusSync' => $statusSync,
            'id_batch' => $id_batch,
        ]);
    }

    public function data(Request $request)
    {
        $request->validate([
            'nim' => 'required',
        ]);

        $prodi_fak = ProgramStudi::where('fakultas_id', auth()->user()->fk_id)
                    ->orderBy('id_jenjang_pendidikan')
                    ->orderBy('nama_program_studi')
                    ->pluck('id_prodi');

        $riwayat = RiwayatPendidikan::with(['prodi.fakultas', 'prodi.jurusan', 'pembimbing_akademik'])
                    ->whereIn('id_prodi', $prodi_fak)
                    ->where('nim', $request->nim)
                    ->orderBy('id_periode_masuk', 'desc')
                    ->first();

        // dd($riwayat);
        if(!$riwayat ) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data Mahasiswa tidak ditemukan!!',
            ]);
        }

        $transkrip = TranskripMahasiswa::where('id_registrasi_mahasiswa', $riwayat->id_registrasi_mahasiswa)->get();

        $total_sks = $transkrip->sum('sks_mata_kuliah');
        $total_indeks = $transkrip->sum('nilai_indeks');

        $ipk = ($total_sks * $total_indeks) / $total_sks;

        $akm = AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa', $riwayat->id_registrasi_mahasiswa)
                ->orderBy('id_semester', 'desc')
                ->get();

        $data = [
            'status' => 'success',
            'data' => $transkrip,
            'akm' => $akm,
            'riwayat' => $riwayat,
            'total_sks' => $total_sks,
            'ipk' => $ipk,
        ];

        return response()->json($data);
    }

    public function download(Request $request)
    {
        $request->validate([
            'nim' => 'required',
        ]);

        $riwayat = RiwayatPendidikan::with(['prodi.fakultas', 'prodi.jurusan', 'pembimbing_akademik'])->where('nim', $request->nim)->orderBy('id_periode_masuk', 'desc')->first();

        if(!$riwayat) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data Mahasiswa tidak ditemukan!!',
            ]);
        }

        $transkrip = TranskripMahasiswa::where('id_registrasi_mahasiswa', $riwayat->id_registrasi_mahasiswa)->get();

        $total_sks = $transkrip->sum('sks_mata_kuliah');
        $bobot = 0;

        foreach ($transkrip as $t) {
            $bobot += $t->nilai_indeks * $t->sks_mata_kuliah;
        }

        $ipk = number_format($bobot / $total_sks, 2);

        $akm = AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa', $riwayat->id_registrasi_mahasiswa)
                ->orderBy('id_semester', 'desc')
                ->get();

        $pdf = PDF::loadview('fakultas.data-akademik.transkrip.pdf', [
            'transkrip' => $transkrip,
            'riwayat' => $riwayat,
            'akm' => $akm,
            'total_sks' => $total_sks,
            'ipk' => $ipk,
         ])
         ->setPaper('a4', 'portrait');
         
         return $pdf->stream('transkrip-'.$riwayat->nim.'.pdf');
    }
}
