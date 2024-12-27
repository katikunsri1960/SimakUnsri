<?php

namespace App\Console\Commands;

use App\Models\Perkuliahan\DosenPengajarKelasKuliah;
use Illuminate\Console\Command;

class FixRencanaDosen extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fix-rencana-dosen';

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
        $semester = '20241';

        // Fetch the data
        $data = DosenPengajarKelasKuliah::leftJoin('kelas_kuliahs as k', 'k.id_kelas_kuliah', 'dosen_pengajar_kelas_kuliahs.id_kelas_kuliah')
                ->join('mata_kuliahs as m', 'm.id_matkul', 'k.id_matkul')
                ->where('dosen_pengajar_kelas_kuliahs.id_semester', $semester)
                ->select('dosen_pengajar_kelas_kuliahs.*', 'm.sks_mata_kuliah as total_sks')
                ->get();

        $groupedData = $data->groupBy('id_kelas_kuliah');
        $this->info($groupedData->count());
        $progressBar = $this->output->createProgressBar($groupedData->count());
        $jumlahKelas = 0;

        foreach ($groupedData as $id_kelas_kuliah => $group) {
            $data = [];
            $sksCheck = 0;

            $jumlahPertemuan = $group->sum('rencana_minggu_pertemuan');
            $count = $group->count();

            if ($jumlahPertemuan > 16 && $count > 1) {
                $jumlahKelas++;

                // $this->info("ID Kelas: {$id_kelas_kuliah}");
                foreach ($group as $g) {
                    $sks_mk = $g->total_sks;
                    $substansi = $g->sks_substansi_total;
                    $rencana = $g->rencana_minggu_pertemuan;
                    $pertemuanCheck = 16;
                    $pertemuanFix = round(($substansi * $pertemuanCheck) / $sks_mk, 0);
                    $data[] = [
                        'id_aktivitas_mengajar' => $g->id_aktivitas_mengajar,
                        'urutan' => $g->urutan,
                        'rencana_minggu_pertemuan' => $pertemuanFix,
                    ];
                }

                $rencana_minggu_pertemuan = collect($data)->sum('rencana_minggu_pertemuan');

                if ($rencana_minggu_pertemuan > 16) {
                    $maxUrutanKey = collect($data)->sortByDesc('urutan')->keys()->first();
                    $data[$maxUrutanKey]['rencana_minggu_pertemuan'] -= round($rencana_minggu_pertemuan - 16, 0);
                } elseif ($rencana_minggu_pertemuan < 16) {
                    // Increase the sks_substansi_total of the item with the lowest urutan
                    $minUrutanKey = collect($data)->sortBy('urutan')->keys()->first();
                    $data[$minUrutanKey]['sks_substansi_total'] += round($rencana_minggu_pertemuan - 16, 0);
                }

                foreach ($data as $d) {
                    DosenPengajarKelasKuliah::where('id_aktivitas_mengajar', $d['id_aktivitas_mengajar'])
                        ->update([
                            'feeder' => 0,
                            'rencana_minggu_pertemuan' => $d['rencana_minggu_pertemuan']
                        ]);
                }
            }

            $progressBar->advance();
        }

        $this->info("Jumlah kelas: {$jumlahKelas}");
    }
}
