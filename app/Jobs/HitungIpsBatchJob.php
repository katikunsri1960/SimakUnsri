<?php

namespace App\Jobs;

use App\Models\Perkuliahan\AktivitasKuliahMahasiswa;
use Illuminate\Bus\Batch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;

class HitungIpsBatchJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $semester;

    /**
     * Create a new job instance.
     *
     * @param  int  $semester
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
            Log::error('Error dalam HitungIpsBatchJob: '.$e->getMessage());
        }
    }
}
