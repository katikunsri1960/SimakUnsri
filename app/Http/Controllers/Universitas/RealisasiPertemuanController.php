<?php

namespace App\Http\Controllers\Universitas;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Bus;
use App\Jobs\Kehadiran\UpdateRealisasiPertemuanJob;
use Illuminate\Http\Request;

class RealisasiPertemuanController extends Controller
{
    // Halaman utama
    public function proses_update_realisasi()
    {
        $jobData = DB::table('job_batches')
            ->where('name', 'sinkronisasi-realisasi-pertemuan')
            ->where('pending_jobs', '>', 0)
            ->first();

        return view('universitas.perkuliahan.kehadiran.realisasi-pertemuan', [
            'statusSync' => $jobData ? 1 : 0,
            'id_batch'   => $jobData->id ?? null,
        ]);
    }

    // Mulai batch update
    public function update_realisasi_pertemuan()
    {
        // Pastikan ada data kehadiran
        if (!DB::table('kehadiran_dosen')->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Data kehadiran dosen masih kosong. Sinkronisasi tidak dapat dilakukan.'
            ], 400);
        }

        // Ambil id_semester aktif (hanya 1 baris)
        $idSemesterAktif = DB::table('semester_aktifs')->value('id_semester');

        if (!$idSemesterAktif) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada semester aktif. Sinkronisasi dibatalkan.'
            ], 400);
        }

        $jobs = [];

        DB::table('dosen_pengajar_kelas_kuliahs as dpk')
            ->join('biodata_dosens as dosen', 'dpk.id_dosen', '=', 'dosen.id_dosen')
            ->where('dpk.id_semester', $idSemesterAktif) // filter semester aktif
            ->select('dpk.id_dosen', 'dpk.id_kelas_kuliah', 'dosen.nip')
            ->orderBy('dpk.id_dosen')
            ->chunk(1000, function ($dosen_kelas) use (&$jobs) {
                $data = json_decode(json_encode($dosen_kelas), true);
                $jobs[] = new UpdateRealisasiPertemuanJob($data);
            });

        $batch = Bus::batch($jobs)
            ->name('sinkronisasi-realisasi-pertemuan')
            ->dispatch();

        return response()->json([
            'success'  => true,
            'message'  => 'Sinkronisasi dimulai.',
            'batch_id' => $batch->id
        ]);
    }

    // Cek progres batch
    public function cek_progres_update_realisasi(Request $request)
    {
        $batch = Bus::findBatch($request->id_batch);

        if (!$batch) {
            return response()->json([
                'success' => false,
                'message' => 'Batch tidak ditemukan atau sudah selesai.'
            ], 404);
        }

        return [
            'total'         => $batch->totalJobs,
            'job_processed' => $batch->processedJobs(),
            'job_pending'   => $batch->pendingJobs,
            'progress'      => $batch->progress(),
        ];
    }
}
