<?php

namespace App\Http\Controllers\Universitas;

use App\Models\Semester;
use Illuminate\Http\Request;
use App\Models\SemesterAktif;
use App\Http\Controllers\Controller;
use App\Models\Perkuliahan\AktivitasKuliahMahasiswa;
use Illuminate\Support\Facades\Bus;

class UpdateAKMController extends Controller
{
    public function akm(Request $request)
    {
        $semesters = Semester::orderBy('id_semester', 'desc')->get();
        $semesterAktif = SemesterAktif::with('semester')->first();
        
        return view('universitas.monitoring.update-akm.index', [
            // return view('fakultas.data-akademik.khs.index',[
            'semesters' => $semesters,
            'semesterAktif' => $semesterAktif,
        ]);
    }

    public function data(Request $request)
    {
        // Tingkatkan batas memori yang dialokasikan
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '2G');


        $semester = $request->semester;
        
        // Ambil data dari database
        $akm = AktivitasKuliahMahasiswa::where('id_semester', $semester)
                        // ->where('id_prodi', '23eded88-d2fe-41ed-a039-a59c7d58a3bb')
                        ->get();
        // $akm = AktivitasKuliahMahasiswa::where('id_semester', $semester)
        //             ->get();

        // Cek jika data kosong
        if ($akm->isEmpty()) {
            $response = [
                'status' => 'error',
                'message' => 'Data AKM pada Semester yang dipilih tidak ditemukan!',
            ];
            return response()->json($response);
        }

        // Berikan respon sukses
        $response = [
            'status' => 'success',
            'message' => 'Data KRS berhasil diambil',
            'akm' => $akm,
        ];

        return response()->json($response);
    }

    public function hitungIps(Request $request)
    {
        // Tingkatkan batas memori yang dialokasikan
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '2G');

        $semester = $request->semester;

        if (!$semester) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tahun akademik harus diisi.'
            ], 400);
        }

        $akmData = AktivitasKuliahMahasiswa::where('id_semester', $semester)
                    ->get();

        $count = $akmData->count();
        $batch = Bus::batch([])->dispatch();
        
        foreach ($akmData as $akm) {
            // $registrasiId = $akm->id_registrasi_mahasiswa;
            
            for($i=0; $i < $count; $i++) {
                $job = new \App\Jobs\HitungIpsJob($semester, $akm->id_registrasi_mahasiswa);
                $batch->add($job);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Proses perhitungan IPS sedang berjalan di latar belakang.'
        ]);
    }
}
