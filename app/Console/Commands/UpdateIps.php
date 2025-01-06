<?php

namespace App\Console\Commands;

use App\Models\Perkuliahan\AktivitasKuliahMahasiswa;
use App\Models\Perkuliahan\KonversiAktivitas;
use App\Models\Perkuliahan\NilaiPerkuliahan;
use App\Models\Perkuliahan\NilaiTransferPendidikan;
use Illuminate\Console\Command;

class UpdateIps extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-ips';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '2048M');

        $semester = '20241';

        $akmData = AktivitasKuliahMahasiswa::where('id_semester', $semester)->whereNot('id_status_mahasiswa', 'N')->get();

        $total = $akmData->count();

        $bar = $this->output->createProgressBar($total);

        foreach ($akmData as $akm) {
            // Contoh perhitungan IPS (sesuaikan logika dengan kebutuhan Anda)
            $khs = NilaiPerkuliahan::where('id_registrasi_mahasiswa', $akm->id_registrasi_mahasiswa)
                ->where('id_semester', $semester)
                ->orderBy('id_semester')
                ->get();

            $khs_konversi = KonversiAktivitas::with(['matkul'])->join('anggota_aktivitas_mahasiswas as ang', 'konversi_aktivitas.id_anggota', 'ang.id_anggota')
                        ->where('id_semester', $semester)
                        ->where('ang.id_registrasi_mahasiswa', $akm->id_registrasi_mahasiswa)
                        ->get();

            $khs_transfer = NilaiTransferPendidikan::where('id_registrasi_mahasiswa', $akm->id_registrasi_mahasiswa)
                        ->where('id_semester', $semester)
                        ->get();

            $total_sks_semester = $khs->sum('sks_mata_kuliah') + $khs_transfer->sum('sks_mata_kuliah_diakui') + $khs_konversi->sum('sks_mata_kuliah');
            $bobot = 0; $bobot_transfer= 0; $bobot_konversi= 0;


            // dd($semester, $tahun_ajaran, $prodi);
            foreach ($khs as $t) {
                $bobot += $t->nilai_indeks * $t->sks_mata_kuliah;
            }

            foreach ($khs_transfer as $tf) {
                $bobot_transfer += $tf->nilai_angka_diakui * $tf->sks_mata_kuliah_diakui;
            }

            foreach ($khs_konversi as $kv) {
                $bobot_konversi += $kv->nilai_indeks * $kv->sks_mata_kuliah;
            }

            $total_bobot= $bobot + $bobot_transfer + $bobot_konversi;

            $ips = 0;
            if($total_sks_semester > 0){
                $ips = $total_bobot / $total_sks_semester;
            }

            // Update nilai IPS pada tabel
            $akm->update([
                'feeder'=>0,
                'ips' => round($ips, 2) // Simpan dengan pembulatan 2 desimal
            ]);

            $bar->advance();
        }

        $this->info('Update IPS success');
    }
}
