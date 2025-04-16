<?php

namespace App\Console\Commands;

use App\Models\Perkuliahan\DosenPengajarKelasKuliah;
use Illuminate\Console\Command;

class FixSksSubstansi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fix-sks-substansi';

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

        // Group the data by 'id_kelas_kuliah'
        $groupedData = $data->groupBy('id_kelas_kuliah');
        $this->info($groupedData->count());
        $progressBar = $this->output->createProgressBar($groupedData->count());
        // die();
        // Process each group
        foreach ($groupedData as $id_kelas_kuliah => $group) {
            $data = [];

            $jumlahPertemuan = $group->sum('rencana_minggu_pertemuan');
            $sksCheck = 0;

            foreach ($group as $g) {
                $pertemuanDosen = $g->rencana_minggu_pertemuan;
                $sks = $g->total_sks;
                $sksCheck = $sks;
                $subTot = round($sks * ($pertemuanDosen / $jumlahPertemuan), 2);
                $data[] = [
                    'id_aktivitas_mengajar' => $g->id_aktivitas_mengajar,
                    'urutan' => $g->urutan,
                    'sks_substansi_total' => $subTot,
                ];
            }

            // Sum $data['sks_substansi_total'] and compare with $sksCheck
            $sks_substansi_total = collect($data)->sum('sks_substansi_total');
            // $this->info($sks_substansi_total);

            if ($sks_substansi_total > $sksCheck) {
                // Decrease the sks_substansi_total of the item with the highest urutan
                $maxUrutanKey = collect($data)->sortByDesc('urutan')->keys()->first();
                $data[$maxUrutanKey]['sks_substansi_total'] -= round($sks_substansi_total - $sksCheck, 2);
            } elseif ($sks_substansi_total < $sksCheck) {
                // Increase the sks_substansi_total of the item with the lowest urutan
                $minUrutanKey = collect($data)->sortBy('urutan')->keys()->first();
                $data[$minUrutanKey]['sks_substansi_total'] += round($sksCheck - $sks_substansi_total, 2);
            }

            $sks_substansi_total2 = collect($data)->sum('sks_substansi_total');

            // if ($sks_substansi_total2 != $sksCheck) {
            //     $this->info("sks_substansi_total2: $sks_substansi_total2");
            //     $this->info(json_encode($data));
            //     $this->info("Total SKS: $sksCheck");
            //     die();
            // }

            // Update the records in the database
            foreach ($data as $d) {
                DosenPengajarKelasKuliah::where('id_aktivitas_mengajar', $d['id_aktivitas_mengajar'])
                    ->update([
                        'feeder' => 0,
                        'sks_substansi_total' => $d['sks_substansi_total'],
                    ]);
            }

            // Update progress bar
            $progressBar->advance();
        }
    }
}
