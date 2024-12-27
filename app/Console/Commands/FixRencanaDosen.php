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

            $jumlahPertemuan = $group->sum('rencana_minggu_pertemuan');

            if ($jumlahPertemuan > 16) {
                $jumlahKelas++;
            }

            $progressBar->advance();
        }

        $this->info("Jumlah kelas: {$jumlahKelas}");
    }
}
