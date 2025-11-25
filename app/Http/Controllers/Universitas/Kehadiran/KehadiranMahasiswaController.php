<?php

namespace App\Http\Controllers\Universitas\Kehadiran;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use App\Jobs\Kehadiran\KehadiranMahasiswaJob;
use App\Models\kehadiran_dosen;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;



class KehadiranMahasiswaController extends Controller
{
    public function kehadiran_mahasiswa(): JsonResponse
    {
        try {
            Log::info('Memulai sinkronisasi kehadiran mahasiswa');

            $allKodeMatkul = kehadiran_dosen::distinct()
                ->whereNotNull('kode_mata_kuliah')
                ->where('kode_mata_kuliah', '!=', '')
                ->pluck('kode_mata_kuliah')
                ->toArray();

            if (empty($allKodeMatkul)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada kode mata kuliah ditemukan.'
                ], 400);
            }

            $batchId = (string) Str::uuid();
            $batchSize = 100;
            $batches = array_chunk($allKodeMatkul, $batchSize);
            $jobs = [];

            foreach ($batches as $batch) {
                $jobs[] = new KehadiranMahasiswaJob($batch, $batchId);
            }

            $batch = Bus::batch($jobs)
                ->name('sinkronisasi-kehadiran-mahasiswa')
                ->onQueue('kehadiran-mahasiswa')
                ->allowFailures()
                ->dispatch();

            return response()->json([
                'success' => true,
                'message' => 'Sinkronisasi kehadiran mahasiswa dimulai.',
                'data' => [
                    'batch_id' => $batch->id,
                    'total_jobs' => $batch->totalJobs,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal memulai sinkronisasi', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memulai proses sinkronisasi: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function proses_update_kehadiran_mahasiswa()
    {
        // Cek dari tabel job_batches seperti pola transkrip()
        $jobData = DB::table('job_batches')
            ->where('name', 'sinkronisasi-kehadiran-mahasiswa')
            ->where('pending_jobs', '>', 0)
            ->first();

        $statusSync = $jobData ? 1 : 0;
        $id_batch = $jobData ? $jobData->id : null;
        return view('universitas.perkuliahan.kehadiran.kehadiran-mahasiswa', [
            'statusSync' => $statusSync,
            'id_batch' => $id_batch
        ]);
    }

    public function cek_sinkronisasi_mahasiswa(Request $request)
    {
        $id_batch = $request->id_batch;
        $batching = Bus::findBatch($id_batch);

        return [
            'total' => $batching->totalJobs,
            'job_processed' => $batching->processedJobs(),
            'job_pending' => $batching->pendingJobs,
            'progress' => $batching->progress(),
        ];
    }
}
