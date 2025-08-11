<?php

namespace App\Jobs;

// use Log;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Mahasiswa\RiwayatPendidikan;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Perkuliahan\NilaiPerkuliahan;
use App\Models\Perkuliahan\KonversiAktivitas;
use App\Models\Perkuliahan\TranskripMahasiswa;
use App\Models\Perkuliahan\NilaiTransferPendidikan;
use App\Models\Perkuliahan\AktivitasKuliahMahasiswa;

class HitungIpsJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    // use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $semester, $id_registrasi_mahasiswa;
    // public $maxTries = 5;
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
        // Tingkatkan batas memori yang dialokasikan
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '2G');

        try {
            $semester = $this->semester;
            $id_registrasi_mahasiswa = $this->id_registrasi_mahasiswa;

            $riwayat = RiwayatPendidikan::where('id_registrasi_mahasiswa', $id_registrasi_mahasiswa)->first();

            $isMaba = $riwayat->id_periode_masuk == $semester ? 1 : 0;
            
            // Ambil semua data KHS, KHS Konversi, dan KHS Transfer secara efisien
            $khsData = NilaiPerkuliahan::where('id_semester', $semester)
                ->where('id_registrasi_mahasiswa', $id_registrasi_mahasiswa)
                ->where('nilai_huruf', '!=', 'F')
                ->get();

            $khsKonversiData = KonversiAktivitas::with(['matkul'])
                ->join('anggota_aktivitas_mahasiswas as ang', 'konversi_aktivitas.id_anggota', 'ang.id_anggota')
                ->where('id_semester', $semester)
                ->where('ang.id_registrasi_mahasiswa', $id_registrasi_mahasiswa)
                ->where('nilai_huruf', '!=', 'F')
                ->get();

            $khsTransferData = NilaiTransferPendidikan::where('id_semester', $semester)
                ->where('id_registrasi_mahasiswa', $id_registrasi_mahasiswa)
                ->where('nilai_huruf_diakui', '!=', 'F')
                ->get();
                
            //SKS SEMESTER
            $totalSksSemester = !$isMaba ? $khsData->sum('sks_mata_kuliah')+ $khsKonversiData->sum('sks_mata_kuliah')+ $khsTransferData->sum('sks_mata_kuliah_diakui') : $khsData->sum('sks_mata_kuliah')+ $khsKonversiData->sum('sks_mata_kuliah');

            // Hitung total bobot
            $bobot = $khsData->sum(function ($item) {
                return $item->nilai_indeks * $item->sks_mata_kuliah;
            });

            $bobotTransfer = $khsKonversiData->sum(function ($item) {
                return $item->nilai_indeks * $item->sks_mata_kuliah;
            });

            $bobotKonversi = $khsTransferData->sum(function ($item) {
                return $item->nilai_angka_diakui * $item->sks_mata_kuliah_diakui;
            });

            $totalBobot = $bobot + $bobotTransfer + $bobotKonversi;

            
            // Hitung IPS
            $ips = $totalSksSemester > 0 ? round($totalBobot / $totalSksSemester, 2) : 0;



            //TRANSKRIP
            $transkrip = TranskripMahasiswa::select(
                        'sks_mata_kuliah','nilai_indeks'
                    )
                    ->where('id_registrasi_mahasiswa', $id_registrasi_mahasiswa)
                    ->whereNotIn('nilai_huruf', ['F', ''])
                    ->get();

            //HITUNG SKS TOTAL
            $sks_total = $transkrip->sum('sks_mata_kuliah');

            $total_bobot_transkrip = 0;
            
            foreach ($transkrip as $t) {
                $total_bobot_transkrip += $t->nilai_indeks * $t->sks_mata_kuliah;
            }

            $ipk = 0;

            if($sks_total > 0){
                $ipk = $total_bobot_transkrip / $sks_total;
            }
                        
            // Update nilai IPS pada tabel
            AktivitasKuliahMahasiswa::where('id_semester', $semester)->where('id_registrasi_mahasiswa', $id_registrasi_mahasiswa)
            ->update([
                'feeder' => 0,
                'sks_semester' => $totalSksSemester,
                'ips' => number_format($ips, 2, '.', ''), // Simpan dengan 2 digit di belakang koma
                'ipk' => number_format($ipk, 2, '.', ''),
                'sks_total' => $sks_total,
            ]);  

            return response()->json(['status' => 'success', 'message' => 'Data Berhasil Diupdate!']);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['status' => 'error', 'message' => 'Data Gagal Diupdate! ']);
        }
    }
}
