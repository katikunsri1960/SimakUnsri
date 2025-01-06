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


    // public function hitungIps(Request $request)
    // {
    //     ini_set('max_execution_time', 0);
    //     ini_set('memory_limit', '2G');
        
    //     try {
    //         // $semester = $request->input('semester');
    //         $semester = $request->semester;

    //         // Validasi input semester
    //         if (!$semester) {
    //             return response()->json([
    //                 'status' => 'error',
    //                 'message' => 'Tahun akademik harus diisi.'
    //             ], 400);
    //         }

    //         // Ambil data AKM mahasiswa berdasarkan semester
    //         $akmData = AktivitasKuliahMahasiswa::where('id_semester', $semester)
    //                     ->where('id_prodi', '23eded88-d2fe-41ed-a039-a59c7d58a3bb')
    //                     ->get();

    //         if ($akmData->isEmpty()) {
    //             return response()->json([
    //                 'status' => 'error',
    //                 'message' => 'Data AKM pada semester yang dipilih tidak ditemukan.'
    //             ], 404);
    //         }

    //         // Ambil semua data KHS, KHS Konversi, dan KHS Transfer secara efisien
    //         $khsData = NilaiPerkuliahan::where('id_semester', $semester)
    //             ->whereIn('id_registrasi_mahasiswa', $akmData->pluck('id_registrasi_mahasiswa'))
    //             ->get();

    //         $khsKonversiData = KonversiAktivitas::with(['matkul'])
    //             ->join('anggota_aktivitas_mahasiswas as ang', 'konversi_aktivitas.id_anggota', 'ang.id_anggota')
    //             ->where('id_semester', $semester)
    //             ->whereIn('ang.id_registrasi_mahasiswa', $akmData->pluck('id_registrasi_mahasiswa'))
    //             ->get();

    //         $khsTransferData = NilaiTransferPendidikan::where('id_semester', $semester)
    //             ->whereIn('id_registrasi_mahasiswa', $akmData->pluck('id_registrasi_mahasiswa'))
    //             ->get();

    //         // Proses data AKM
    //         foreach ($akmData as $akm) {
    //             $registrasiId = $akm->id_registrasi_mahasiswa;

    //             // Filter data KHS, KHS Konversi, dan KHS Transfer untuk mahasiswa saat ini
    //             $khs = $khsData->where('id_registrasi_mahasiswa', $registrasiId);
    //             $khsKonversi = $khsKonversiData->where('id_registrasi_mahasiswa', $registrasiId);
    //             $khsTransfer = $khsTransferData->where('id_registrasi_mahasiswa', $registrasiId);

    //             // Hitung total SKS semester
    //             $totalSksSemester = $khs->sum('sks_mata_kuliah')
    //                 + $khsTransfer->sum('sks_mata_kuliah_diakui')
    //                 + $khsKonversi->sum('sks_mata_kuliah');

    //             // Hitung total bobot
    //             $bobot = $khs->sum(function ($item) {
    //                 return $item->nilai_indeks * $item->sks_mata_kuliah;
    //             });

    //             $bobotTransfer = $khsTransfer->sum(function ($item) {
    //                 return $item->nilai_angka_diakui * $item->sks_mata_kuliah_diakui;
    //             });

    //             $bobotKonversi = $khsKonversi->sum(function ($item) {
    //                 return $item->nilai_indeks * $item->sks_mata_kuliah;
    //             });

    //             $totalBobot = $bobot + $bobotTransfer + $bobotKonversi;

    //             // Hitung IPS
    //             $ips = $totalSksSemester > 0 ? round($totalBobot / $totalSksSemester, 2) : 0;

    //             // Update nilai IPS pada tabel
    //             $akm->update([
    //                 'feeder' => 0,
    //                 'ips' => number_format($ips, 2, '.', '') // Simpan dengan 2 digit di belakang koma
    //             ]);                
    //         }

    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'Nilai IPS berhasil dihitung dan diperbarui.'
    //         ]);
            
    //     } catch (\Exception $e) {
    //         // Tangani error secara umum
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Terjadi kesalahan: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }

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
            $registrasiId = $akm->id_registrasi_mahasiswa;
            
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
