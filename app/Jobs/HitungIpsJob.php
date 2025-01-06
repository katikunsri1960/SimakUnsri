<?php

namespace App\Jobs;

// use Log;
use Illuminate\Support\Facades\Log;
use Illuminate\Bus\Queueable;
use Illuminate\Bus\Batchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Perkuliahan\NilaiPerkuliahan;
use App\Models\Perkuliahan\KonversiAktivitas;
use App\Models\Perkuliahan\NilaiTransferPendidikan;
use App\Models\Perkuliahan\AktivitasKuliahMahasiswa;

class HitungIpsJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $semester, $id_registrasi_mahasiswa;

    /**
     * Create a new job instance.
     *
     * @param int $semester
     */
    public function __construct($semester, $id_registrasi_mahasiswa)
    {
        $this->semester = $semester;
        $this->id_registrasi_mahasiswa = $id_registrasi_mahasiswa;
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
            $id_registrasi_mahasiswa = $this->id_registrasi_mahasiswa;

            
            // Ambil semua data KHS, KHS Konversi, dan KHS Transfer secara efisien
            $khsData = NilaiPerkuliahan::where('id_semester', $semester)
                ->whereIn('id_registrasi_mahasiswa', $id_registrasi_mahasiswa)
                ->get();

            $khsKonversiData = KonversiAktivitas::with(['matkul'])
                ->join('anggota_aktivitas_mahasiswas as ang', 'konversi_aktivitas.id_anggota', 'ang.id_anggota')
                ->where('id_semester', $semester)
                ->whereIn('ang.id_registrasi_mahasiswa', $id_registrasi_mahasiswa)
                ->get();

            $khsTransferData = NilaiTransferPendidikan::where('id_semester', $semester)
                ->whereIn('id_registrasi_mahasiswa', $id_registrasi_mahasiswa)
                ->get();

            // Proses data AKM
                
                // Filter data KHS, KHS Konversi, dan KHS Transfer untuk mahasiswa saat ini
                $khs = $khsData->where('id_registrasi_mahasiswa', $id_registrasi_mahasiswa);
                $khsKonversi = $khsKonversiData->where('id_registrasi_mahasiswa', $id_registrasi_mahasiswa);
                $khsTransfer = $khsTransferData->where('id_registrasi_mahasiswa', $id_registrasi_mahasiswa);

                // Hitung total SKS semester
                $totalSksSemester = $khs->sum('sks_mata_kuliah')
                    + $khsTransfer->sum('sks_mata_kuliah_diakui')
                    + $khsKonversi->sum('sks_mata_kuliah');

                // Hitung total bobot
                $bobot = $khs->sum(function ($item) {
                    return $item->nilai_indeks * $item->sks_mata_kuliah;
                });

                $bobotTransfer = $khsTransfer->sum(function ($item) {
                    return $item->nilai_angka_diakui * $item->sks_mata_kuliah_diakui;
                });

                $bobotKonversi = $khsKonversi->sum(function ($item) {
                    return $item->nilai_indeks * $item->sks_mata_kuliah;
                });

                $totalBobot = $bobot + $bobotTransfer + $bobotKonversi;

                // Hitung IPS
                $ips = $totalSksSemester > 0 ? round($totalBobot / $totalSksSemester, 2) : 0;

                // Update nilai IPS pada tabel
                AktivitasKuliahMahasiswa::where('id_semester', $semester)->where('id_registrasi_mahasiswa', $id_registrasi_mahasiswa)->update([
                    'feeder' => 0,
                    'ips' => number_format($ips, 2, '.', '') // Simpan dengan 2 digit di belakang koma
                ]);  

        } catch (\Exception $e) {
            Log::error('Error menghitung IPS: ' . $e->getMessage());
        }
    }
}
