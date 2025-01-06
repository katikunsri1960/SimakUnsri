<?php

namespace App\Jobs;

use App\Models\Perkuliahan\AktivitasKuliahMahasiswa;
use App\Models\Perkuliahan\NilaiPerkuliahan;
use App\Models\Perkuliahan\KonversiAktivitas;
use App\Models\Perkuliahan\NilaiTransferPendidikan;
use App\Jobs\HitungIpsJob;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class HitungIpsBatchJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $semester;

    /**
     * Create a new job instance.
     *
     * @param int $semester
     */
    public function __construct($semester)
    {
        $this->semester = $semester;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $semester = $this->semester;

            // Ambil data AKM mahasiswa berdasarkan semester
            $akmData = AktivitasKuliahMahasiswa::where('id_semester', $semester)->get();

            if ($akmData->isEmpty()) {
                return; // Tidak ada data, hentikan proses
            }

            // Buat array untuk menampung jobs
            $jobs = [];

            // Proses data per mahasiswa dengan job perhitungan IPS per mahasiswa
            foreach ($akmData as $akm) {
                $jobs[] = new HitungIpsJob($semester, $akm->id_registrasi_mahasiswa);
            }

            // Jalankan batch job
            Bus::batch($jobs)
                ->name('Hitung IPS Batch Job')
                ->dispatch();

        } catch (\Exception $e) {
            Log::error('Error dalam HitungIpsBatchJob: ' . $e->getMessage());
        }
    }
}
