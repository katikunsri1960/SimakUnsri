<?php

namespace App\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Mahasiswa\RiwayatPendidikan;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Perkuliahan\NilaiPerkuliahan;
use App\Models\Perkuliahan\AktivitasMahasiswa;
use App\Models\Perkuliahan\KonversiAktivitas;
use App\Models\Perkuliahan\PesertaKelasKuliah;
use App\Models\Perkuliahan\TranskripMahasiswa;
use App\Models\Perkuliahan\NilaiTransferPendidikan;
use App\Models\Perkuliahan\AktivitasKuliahMahasiswa;
use App\Models\Perkuliahan\KelasKuliah;

class HitungIpsJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $semester, $id_registrasi_mahasiswa;

    public function __construct($semester, $id_registrasi_mahasiswa)
    {
        $this->semester = $semester;
        $this->id_registrasi_mahasiswa = $id_registrasi_mahasiswa;
    }

    public function handle()
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '2G');

        try {
            $semester = $this->semester;
            $id_registrasi_mahasiswa = $this->id_registrasi_mahasiswa;

            $riwayat = RiwayatPendidikan::where('id_registrasi_mahasiswa', $id_registrasi_mahasiswa)->first();

            $isMaba = ($riwayat->id_periode_masuk == $semester) ? 1 : 0;

            if (!$riwayat) {
                return;
            }

            $idKelasSemester = KelasKuliah::where('id_semester', $semester)
                ->pluck('id_kelas_kuliah');

            $krsData = PesertaKelasKuliah::with(['matkul', 'kelas_kuliah'])
                ->where('id_registrasi_mahasiswa', $id_registrasi_mahasiswa)
                ->where('approved', 1)
                ->whereIn('id_kelas_kuliah', $idKelasSemester)
                ->get();
                
            // 1. Ambil data KRS Perkuliahan
            // $krsData = PesertaKelasKuliah::with(['matkul', 'kelas_kuliah'])
            //     ->where('id_registrasi_mahasiswa', $id_registrasi_mahasiswa)
            //     ->where('approved', 1)
            //     ->whereHas('kelas_kuliah', function ($query) use ($semester) {
            //         $query->where('id_semester', $semester);
            //     })
            //     ->get();

            // 2. Ambil data KRS Aktivitas (Non-MBKM)
            $krsAktivitasData = AktivitasMahasiswa::with(['konversi', 'anggota_aktivitas_personal'])
                ->where('id_semester', $semester)
                ->where('approve_krs', 1)
                ->whereNull('sks_aktivitas')
                ->whereHas('anggota_aktivitas_personal', function ($query) use ($id_registrasi_mahasiswa) {
                    $query->where('id_registrasi_mahasiswa', $id_registrasi_mahasiswa);
                })
                ->get();

            // 3. Ambil data KRS MBKM
            $krsMBKMData = AktivitasMahasiswa::with(['anggota_aktivitas_personal'])
                ->where('id_semester', $semester)
                ->where('approve_krs', 1)
                ->whereNotNull('sks_aktivitas')
                ->whereHas('anggota_aktivitas_personal', function ($query) use ($id_registrasi_mahasiswa) {
                    $query->where('id_registrasi_mahasiswa', $id_registrasi_mahasiswa);
                })
                ->get();

            // 4. Ambil data KHS & Transfer
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

            // 5. Hitung SKS Semester (Perbaikan akumulasi)
            $sksKrsMatkul    = $krsData->sum(fn($item) => $item->matkul->sks_mata_kuliah ?? 0);
            $sksKrsAktivitas = $krsAktivitasData->sum(fn($item) => $item->konversi->sks_mata_kuliah ?? 0);
            $sksKrsMBKM      = $krsMBKMData->sum('sks_aktivitas');
            $sksTransfer     = $khsTransferData->sum('sks_mata_kuliah_diakui');

            $totalSksSemester = !$isMaba 
                ? ($sksKrsMatkul + $sksKrsAktivitas + $sksKrsMBKM + $sksTransfer)
                : ($sksKrsMatkul + $sksKrsAktivitas + $sksKrsMBKM);

            // 6. Hitung Total Bobot Nilai
            $bobot = $khsData->sum(function ($item) {
                return $item->nilai_indeks * $item->sks_mata_kuliah;
            });

            $bobotKonversi = $khsKonversiData->sum(function ($item) {
                return $item->nilai_indeks * $item->sks_mata_kuliah;
            });

            $bobotTransfer = $khsTransferData->sum(function ($item) {
                return $item->nilai_angka_diakui * $item->sks_mata_kuliah_diakui;
            });

            $totalBobot = $bobot + $bobotKonversi + $bobotTransfer;

            // 7. Hitung SKS Dinilai & IPS
            $totalSksDinilai = $khsData->sum('sks_mata_kuliah')
                + $khsKonversiData->sum('sks_mata_kuliah')
                + $khsTransferData->sum('sks_mata_kuliah_diakui');

            $ips = $totalSksDinilai > 0 ? round($totalBobot / $totalSksDinilai, 2) : 0;

            // 8. Hitung Transkrip (IPK & SKS Total)
            $transkrip = TranskripMahasiswa::select('sks_mata_kuliah', 'nilai_indeks')
                ->where('id_registrasi_mahasiswa', $id_registrasi_mahasiswa)
                ->whereNotIn('nilai_huruf', ['F', ''])
                ->get();

            $sks_total = $transkrip->sum('sks_mata_kuliah');
            $total_bobot_transkrip = 0;

            foreach ($transkrip as $t) {
                $total_bobot_transkrip += ($t->nilai_indeks * $t->sks_mata_kuliah);
            }

            $ipk = $sks_total > 0 ? ($total_bobot_transkrip / $sks_total) : 0;

            // 9. Update Database AKM
            AktivitasKuliahMahasiswa::where('id_semester', $semester)
                ->where('id_registrasi_mahasiswa', $id_registrasi_mahasiswa)
                ->update([
                    'feeder'       => 0,
                    'sks_semester' => $totalSksSemester,
                    'ips'          => number_format($ips, 2, '.', ''),
                    'ipk'          => number_format($ipk, 2, '.', ''),
                    'sks_total'    => $sks_total,
                ]);

        } catch (\Throwable $th) {
            Log::error('Error HitungIpsJob: ' . $th->getMessage());
        }
    }
}