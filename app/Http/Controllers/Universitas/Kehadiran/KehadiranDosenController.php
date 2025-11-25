<?php

namespace App\Http\Controllers\Universitas\Kehadiran;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use App\Jobs\Kehadiran\KehadiranDosenJob;
use App\Models\mk_kelas;
use Illuminate\Http\Request;


class KehadiranDosenController extends Controller
{
    public function kehadiran_dosen(): JsonResponse
    {
        try {
            Log::info('Memulai sinkronisasi kehadiran dosen');

            $url = config('services.moodle.ws_url');
            $token = config('services.moodle.ws_token');

            try {
                $check = Http::timeout(5)->get($url, [
                    'wstoken' => $token,
                    'wsfunction' => 'core_webservice_get_site_info',
                    'moodlewsrestformat' => 'json',
                ]);
            } catch (\Exception $ex) {
                Log::error('Gagal koneksi ke Moodle', [
                    'url' => $url,
                    'error' => $ex->getMessage(),
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak bisa terhubung ke E-learning (network error)',
                    'error'   => $ex->getMessage(), // contoh: getaddrinfo EAI_AGAIN
                ], 500);
            }

            if (!$check->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Moodle tidak bisa diakses. Status: ' . $check->status(),
                    'error'   => $check->body(),
                ], 503);
            }
            // Ambil kode MK
            $allKodeMatkul = mk_kelas::distinct()
                ->whereNotNull('kode_mata_kuliah')
                ->where('kode_mata_kuliah', '!=', '')
                ->pluck('kode_mata_kuliah')
                ->toArray();

            if (empty($allKodeMatkul)) {
                return response()->json(['success' => false, 'message' => 'Tidak ada kode mata kuliah ditemukan.'], 400);
            }

            $batchId = (string) Str::uuid();
            $batchSize = 100;
            $batches = array_chunk($allKodeMatkul, $batchSize);
            $jobs = [];
            foreach ($batches as $batch) {
                $jobs[] = new KehadiranDosenJob($batch, $batchId);
            }

            $batch = Bus::batch($jobs)
                ->name('sinkronisasi-kehadiran-dosen')
                ->onQueue('kehadiran-dosen')
                ->allowFailures()
                ->dispatch();

            return response()->json([
                'success' => true,
                'message' => 'Sinkronisasi kehadiran dosen dimulai.',
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


    public function proses_update()
    {
        // Cek dari tabel job_batches seperti pola transkrip()
        $jobData = DB::table('job_batches')
            ->where('name', 'sinkronisasi-kehadiran-dosen')
            ->where('pending_jobs', '>', 0)
            ->first();

        $statusSync = $jobData ? 1 : 0;
        $id_batch = $jobData ? $jobData->id : null;
        return view('universitas.perkuliahan.kehadiran.kehadiran-dosen', [
            'statusSync' => $statusSync,
            'id_batch' => $id_batch,
        ]);
    }

    public function cek_progres(Request $request)
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
