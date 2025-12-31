<?php

namespace App\Http\Controllers\Universitas\Kehadiran;

use App\Http\Controllers\Controller;
use App\Jobs\Kehadiran\MataKuliahElearningJob;
use App\Models\SemesterAktif; // Pastikan import Model ini
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class NamaMataKuliahController extends Controller
{
    public function semester_aktif()
    {
        try {
            // 1. Ambil ID Semester (Hanya 1 nilai string/int)
            $idSemester = SemesterAktif::latest()->value('id_semester');

            if (!$idSemester) {
                throw new \Exception("Semester Aktif tidak ditemukan.");
            }

            // 2. Ambil DATA MATA KULIAH (Ini yang banyak, misal 2000 baris)
            // Kita simpan di variabel bernama $dataMataKuliah biar jelas
            $dataMataKuliah = DB::table('semester_aktifs')
                ->join('kelas_kuliahs', 'semester_aktifs.id_semester', '=', 'kelas_kuliahs.id_semester')
                ->join('matkul_kurikulums', 'kelas_kuliahs.id_matkul', '=', 'matkul_kurikulums.id_matkul')
                ->where('semester_aktifs.id_semester', $idSemester)
                ->select('matkul_kurikulums.kode_mata_kuliah', 'kelas_kuliahs.nama_kelas_kuliah', 'kelas_kuliahs.id_kelas_kuliah')
                ->get(); 

            // 3. Buat Batch Kosong Dulu
            $batch = Bus::batch([])
                ->name('mk-elearning')
                ->allowFailures()
                ->dispatch();

            // 4. CHUNK DATA-NYA
            // Kita potong $dataMataKuliah menjadi paket-paket isi 100
            $chunks = $dataMataKuliah->chunk(1000); 

            foreach ($chunks as $chunkData) {
                $jobs = [];
                
                // Loop data pecahan (isi 100)
                foreach ($chunkData as $item) {
                    // Kita kirim $item (datanya) dan $idSemester (sebagai info tambahan) ke Job
                    $jobs[] = new MataKuliahElearningJob($item, $idSemester);
                }
                
                // Masukkan 100 job ini ke antrian batch secara bertahap
                // Ini mencegah error "MySQL server has gone away"
                $batch->add($jobs);
            }

            return response()->json([
                'success'  => true,
                'status'   => 'Proses sinkronisasi sedang berjalan di background',
                'batch_id' => $batch->id,
                'total_data' => $dataMataKuliah->count()
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memulai sinkronisasi',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    // ... method proses_ambil_mk dan cek_progres_ambil_mk TETAP SAMA ...
    public function proses_ambil_mk()
    {
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

        if (!$batching) {
            return [
                'total' => 0,
                'job_processed' => 0,
                'job_pending' => 0,
                'progress' => 100, // Asumsi selesai jika batch tidak ketemu
            ];
        }

        return [
            'total' => $batching->totalJobs,
            'job_processed' => $batching->processedJobs(),
            'job_pending' => $batching->pendingJobs,
            'progress' => $batching->progress(),
        ];
    }
}