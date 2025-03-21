<?php

namespace App\Http\Controllers\Fakultas\Akademik;

use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Connection\Usept;
use Illuminate\Support\Facades\DB;
use App\Models\Perpus\BebasPustaka;
use App\Http\Controllers\Controller;
use App\Models\Connection\CourseUsept;
use App\Models\Perkuliahan\ListKurikulum;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\PejabatFakultas;
use App\Models\Perkuliahan\TranskripMahasiswa;
use App\Models\Perkuliahan\AktivitasKuliahMahasiswa;
use Carbon\Carbon;

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

        $jobData =  DB::table('job_batches')->where('name', 'transkrip-mahasiswa')->where('pending_jobs', '>', 0)->first();

        $statusSync = $jobData ? 1 : 0;

        if ($statusSync) {
            return response()->json([
            'status' => 'error',
            'message' => 'Tidak dapat mencari data, proses sinkronisasi sedang berjalan!!',
            'refresh' => true,
            'route' => route('fakultas.data-akademik.transkrip-nilai'),
            ]);
        }

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

        if(!$riwayat->id_kurikulum ) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kurikulum Mahasiswa Belum Diatur!!',
            ]);
        }else{
            $nilai_usept_prodi = ListKurikulum::where('id_kurikulum', $riwayat->id_kurikulum)->first();
        }

        try {
            set_time_limit(10);

            $nilai_usept_mhs = Usept::whereIn('nim', [$riwayat->nim, $riwayat->biodata->nik])->pluck('score');
            $nilai_course = CourseUsept::whereIn('nim', [$riwayat->nim, $riwayat->biodata->nik])->get()->pluck('konversi');

            $all_scores = $nilai_usept_mhs->merge($nilai_course);
            $usept = $all_scores->max();

            $useptData = [
                'score' => $usept,
                'class' => $usept < $nilai_usept_prodi->nilai_usept ? 'danger' : 'success',
                'status' => $usept < $nilai_usept_prodi->nilai_usept ? 'Tidak memenuhi Syarat' : 'Memenuhi Syarat',
            ];

        } catch (\Throwable $th) {
            //throw $th;
            $useptData = [
                'score' => 0,
                'class' => 'danger',
                'status' => 'Database USEPT tidak bisa diakses, silahkan hubungi pengelola USEPT.',
            ];
        }

        $transkrip = TranskripMahasiswa::where('id_registrasi_mahasiswa', $riwayat->id_registrasi_mahasiswa)->get();

        $total_sks = $transkrip->sum('sks_mata_kuliah');
        $total_indeks = $transkrip->sum('nilai_indeks');

        $ipk = ($total_sks * $total_indeks) / $total_sks;

        $akm = AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa', $riwayat->id_registrasi_mahasiswa)
                ->orderBy('id_semester', 'desc')
                ->get();

        $bebas_pustaka = BebasPustaka::where('id_registrasi_mahasiswa', $riwayat->id_registrasi_mahasiswa)->first();

        $data = [
            'status' => 'success',
            'data' => $transkrip,
            'akm' => $akm,
            'riwayat' => $riwayat,
            'total_sks' => $total_sks,
            'ipk' => $ipk,
            'bebas_pustaka' => $bebas_pustaka,
            'usept' => $useptData,
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
        // $nilai_mutu = $transkrip->sum('sks_mata_kuliah')*$transkrip->sum('nilai_');
        $bobot = 0;

        foreach ($transkrip as $t) {
            $bobot += $t->nilai_indeks * $t->sks_mata_kuliah;
        }

        $ipk = number_format($bobot / $total_sks, 2);
        // dd($ipk);
        $akm = AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa', $riwayat->id_registrasi_mahasiswa)
                ->orderBy('id_semester', 'desc')
                ->get();

        $pdf = PDF::loadview('fakultas.data-akademik.transkrip.pdf', [
            'transkrip' => $transkrip,
            'riwayat' => $riwayat,
            'akm' => $akm,
            'total_sks' => $total_sks,
            'ipk' => $ipk,
            'bobot'=> $bobot,
            'today'=> Carbon::now(),
            'wd1' => PejabatFakultas::where('id_fakultas', $riwayat->prodi->fakultas_id)->where('id_jabatan', 1)->first(),
            'bebas_pustaka' => BebasPustaka::where('id_registrasi_mahasiswa', $riwayat->id_registrasi_mahasiswa)->first(),
         ])
         ->setPaper('a4', 'portrait');
         
         return $pdf->stream('transkrip-'.$riwayat->nim.'.pdf');
    }
}
