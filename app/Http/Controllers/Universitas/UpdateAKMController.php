<?php

namespace App\Http\Controllers\Universitas;

use App\Http\Controllers\Controller;
use App\Jobs\HitungIpsJob;
use App\Models\Perkuliahan\AktivitasKuliahMahasiswa;
use App\Models\Semester;
use App\Models\SemesterAktif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;

class UpdateAKMController extends Controller
{
    public function akm(Request $request)
    {
        $semesters = Semester::orderBy('id_semester', 'desc')->get();
        $semesterAktif = SemesterAktif::with('semester')->first();

        return view('universitas.monitoring.update-akm.index', [
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
        $akm = AktivitasKuliahMahasiswa::where('id_semester', $semester)->get();

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
        // Tingkatkan batas memori dan waktu eksekusi
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '2G');

        $semester = $request->semester;

        if (! $semester) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tahun akademik harus diisi.',
            ], 400);
        }

        // Batasi ukuran chunk
        $chunkSize = 500; // Sesuaikan ukuran chunk sesuai kebutuhan

        // Ambil data AKM dalam chunk
        AktivitasKuliahMahasiswa::where('id_semester', $semester)
            ->chunk($chunkSize, function ($akmData) use ($semester) {
                $jobs = [];

                // Siapkan job untuk setiap data dalam chunk
                foreach ($akmData as $akm) {
                    $jobs[] = new HitungIpsJob($semester, $akm->id_registrasi_mahasiswa);
                }

                // Tambahkan job ke batch
                Bus::batch($jobs)->dispatch();
            });

        return response()->json([
            'status' => 'success',
            'message' => 'Proses perhitungan IPS sedang berjalan di latar belakang.',
        ]);
    }
}
