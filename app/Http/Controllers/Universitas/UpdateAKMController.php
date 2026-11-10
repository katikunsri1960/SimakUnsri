<?php

namespace App\Http\Controllers\Universitas;

use App\Models\Semester;
use App\Jobs\HitungIpsJob;
use Illuminate\Http\Request;
use App\Models\SemesterAktif;
use Illuminate\Support\Facades\Bus;
use App\Http\Controllers\Controller;
use App\Models\Perkuliahan\TranskripMahasiswa;
use App\Models\Perkuliahan\AktivitasKuliahMahasiswa;
use App\Models\Perkuliahan\PesertaKelasKuliah;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Perkuliahan\KelasKuliah;
use App\Models\Perkuliahan\AktivitasMahasiswa;
use App\Models\Perkuliahan\NilaiTransferPendidikan;

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
        $akm = AktivitasKuliahMahasiswa::where('id_semester', $semester)
            // ->where('nim', '08051182126003')
            ->get();
        
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
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '2G');

        $semester = $request->semester;

        if (!$semester) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tahun akademik harus diisi.'
            ], 400);
        }

        $chunkSize = 500;
        AktivitasKuliahMahasiswa::where('id_semester', $semester)
            ->select('id_registrasi_mahasiswa')
            // ->where('nim', '08051182126003')
            ->chunk($chunkSize, function ($akmData) use ($semester) {
                $jobs = [];
                foreach ($akmData as $akm) {
                    $jobs[] = new HitungIpsJob($semester, $akm->id_registrasi_mahasiswa);
                }
                if (count($jobs) > 0) {
                    $batchName = 'Hitung IPS Semester ' . $semester;
                    Bus::batch($jobs)
                        ->name($batchName)
                        ->dispatch();
                }
            });
            
        return response()->json([
            'status' => 'success',
            'message' => 'Proses perhitungan IPS sedang berjalan di latar belakang.',
            // 'sks_total' => [$sks_total],
        ]);
    }


    public function testHitungIps(Request $request)
{
    $semester = $request->query('semester'); // contoh: 20252
    $nim      = $request->query('nim', '01010182428028');

    if (!$semester) {
        return response()->json(['error' => 'Parameter semester wajib diisi!'], 400);
    }

    // 1. Cari Riwayat Pendidikan berdasarkan NIM
    $riwayat = RiwayatPendidikan::where('nim', $nim)->first();

    if (!$riwayat) {
        return response()->json([
            'status' => 'error',
            'message' => 'Data RiwayatPendidikan mahasiswa tidak ditemukan untuk NIM: ' . $nim
        ]);
    }

    $id_registrasi_mahasiswa = $riwayat->id_registrasi_mahasiswa;
    $isMaba = ($riwayat->id_periode_masuk == $semester) ? 1 : 0;

    // 2. Query KRS Mata Kuliah (Perbaikan: Menggunakan whereIn menggantikan whereHas)
    $idKelasSemester = KelasKuliah::where('id_semester', $semester)
        ->pluck('id_kelas_kuliah');

    $krsData = PesertaKelasKuliah::with(['matkul', 'kelas_kuliah'])
        ->where('id_registrasi_mahasiswa', $id_registrasi_mahasiswa)
        ->where('approved', 1)
        ->whereIn('id_kelas_kuliah', $idKelasSemester)
        ->get();

        // dd($krsData);

    // 3. Query KRS Aktivitas (Non-MBKM)
    $krsAktivitasData = AktivitasMahasiswa::with(['konversi', 'anggota_aktivitas_personal'])
        ->where('id_semester', $semester)
        ->where('approve_krs', 1)
        ->whereNull('sks_aktivitas')
        ->whereHas('anggota_aktivitas_personal', function ($query) use ($id_registrasi_mahasiswa) {
            $query->where('id_registrasi_mahasiswa', $id_registrasi_mahasiswa);
        })
        ->get();

    // 4. Query KRS MBKM
    $krsMBKMData = AktivitasMahasiswa::with(['anggota_aktivitas_personal'])
        ->where('id_semester', $semester)
        ->where('approve_krs', 1)
        ->whereNotNull('sks_aktivitas')
        ->whereHas('anggota_aktivitas_personal', function ($query) use ($id_registrasi_mahasiswa) {
            $query->where('id_registrasi_mahasiswa', $id_registrasi_mahasiswa);
        })
        ->get();

    // 5. Query KHS Transfer
    $khsTransferData = NilaiTransferPendidikan::where('id_semester', $semester)
        ->where('id_registrasi_mahasiswa', $id_registrasi_mahasiswa)
        ->where('nilai_huruf_diakui', '!=', 'F')
        ->get();

    // 6. Rincian Penjumlahan SKS
    $sksKrsMatkul    = $krsData->sum(fn($item) => $item->matkul->sks_mata_kuliah ?? 0);
    $sksKrsAktivitas = $krsAktivitasData->sum(fn($item) => $item->konversi->sks_mata_kuliah ?? 0);
    $sksKrsMBKM      = $krsMBKMData->sum('sks_aktivitas');
    $sksTransfer     = $khsTransferData->sum('sks_mata_kuliah_diakui');

    $totalSksSemester = !$isMaba 
        ? ($sksKrsMatkul + $sksKrsAktivitas + $sksKrsMBKM + $sksTransfer)
        : ($sksKrsMatkul + $sksKrsAktivitas + $sksKrsMBKM);

    // 7. Cek Record AKM Sebelum Diupdate
    $akmBefore = AktivitasKuliahMahasiswa::where('id_semester', $semester)
        ->where('id_registrasi_mahasiswa', $id_registrasi_mahasiswa)
        ->first();

    // Eksekusi Update Langsung
    $affectedRows = AktivitasKuliahMahasiswa::where('id_semester', $semester)
        ->where('id_registrasi_mahasiswa', $id_registrasi_mahasiswa)
        ->update([
            'sks_semester' => $totalSksSemester
        ]);

    return response()->json([
        'info_mahasiswa' => [
            'nim'                     => $nim,
            'id_registrasi_mahasiswa' => $id_registrasi_mahasiswa,
            'id_periode_masuk'        => $riwayat->id_periode_masuk,
            'semester_diuji'          => $semester,
            'is_maba'                 => $isMaba,
        ],
        'krs_matkul' => $krsData->map(function ($item) {
            return [
                'id_peserta_kelas_kuliah' => $item->id_peserta_kelas_kuliah ?? $item->id,
                'id_kelas_kuliah'         => $item->id_kelas_kuliah,
                'nama_kelas'              => $item->kelas_kuliah->nama_kelas_kuliah ?? '-',
                'kode_mata_kuliah'        => $item->matkul->kode_mata_kuliah ?? '-',
                'nama_mata_kuliah'        => $item->matkul->nama_mata_kuliah ?? '-',
                'sks_mata_kuliah'         => $item->matkul->sks_mata_kuliah ?? 0,
                'approved'                => $item->approved,
                'id_semester_kelas'       => $item->kelas_kuliah->id_semester ?? null,
            ];
        }),
        'rincian_sks' => [
            'sks_krs_matkul'                  => $sksKrsMatkul,
            'sks_krs_aktivitas'               => $sksKrsAktivitas,
            'sks_krs_mbkm'                    => $sksKrsMBKM,
            'sks_transfer'                    => $sksTransfer,
            'total_sks_semester_hasil_hitung' => $totalSksSemester
        ],
        'status_db_update' => [
            'record_akm_ditemukan' => $akmBefore ? true : false,
            'sks_semester_lama'    => $akmBefore->sks_semester ?? null,
            'baris_terupdate'      => $affectedRows
        ],
        'debug_raw_data' => [
            'count_krs_matkul'    => $krsData->count(),
            'count_krs_aktivitas' => $krsAktivitasData->count(),
            'count_krs_mbkm'      => $krsMBKMData->count(),
        ]
    ]);
}

}