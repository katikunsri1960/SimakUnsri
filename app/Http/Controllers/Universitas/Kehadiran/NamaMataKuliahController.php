<?php

namespace App\Http\Controllers\Universitas\Kehadiran;

use App\Http\Controllers\Controller;
use App\Jobs\Kehadiran\MataKuliahElearningJob;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;



class NamaMataKuliahController extends Controller
{

    public function semester_aktif()
    {

        try {
            $batch = Bus::batch([
                new MataKuliahElearningJob(),
            ])
                ->name('mk-elearning')
                ->allowFailures()
                ->dispatch();

            return response()->json([
                'success'  => true, // 👈 Tambahkan ini
                'status'   => 'Proses sinkronisasi sedang berjalan di background',
                'batch_id' => $batch->id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, // 👈 Tambahkan ini
                'message' => 'Gagal memulai sinkronisasi',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function proses_ambil_mk()
    {

        // Cek dari tabel job_batches seperti pola transkrip()
        $jobData = DB::table('job_batches')
            ->where('name', 'mk-elearning')
            ->where('pending_jobs', '>', 0)
            ->first();

        $statusSync = $jobData ? 1 : 0;
        $id_batch = $jobData ? $jobData->id : null;

        return view('universitas.perkuliahan.kehadiran.mk-elearning', [
            'statusSync' => $statusSync,
            'id_batch' => $id_batch
        ]);
    }

    public function cek_progres_ambil_mk(Request $request)
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
