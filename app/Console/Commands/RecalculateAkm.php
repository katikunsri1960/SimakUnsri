<?php

namespace App\Console\Commands;

use App\Models\Perkuliahan\AktivitasKuliahMahasiswa;
use App\Models\Perkuliahan\TranskripMahasiswa;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RecalculateAkm extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:recalculate-akm';

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

        ini_set('memory_limit', '2048M');

        $semester = '20241';

        $data = AktivitasKuliahMahasiswa::where('id_semester', $semester)->get();

        $totalData = $data->count();

        // progress bar
        $bar = $this->output->createProgressBar($totalData);

        foreach ($data as $d)
        {
            $transkrip = TranskripMahasiswa::select(
                DB::raw('SUM(CAST(sks_mata_kuliah AS UNSIGNED)) as total_sks'), // Mengambil total SKS tanpa nilai desimal
                DB::raw('ROUND(SUM(nilai_indeks * sks_mata_kuliah) / SUM(sks_mata_kuliah), 2) as ipk') // Mengambil IPK dengan 2 angka di belakang koma
            )
            ->where('id_registrasi_mahasiswa', $d->id_registrasi_mahasiswa)
            ->whereNotIn('nilai_huruf', ['F', ''])
            ->groupBy('id_registrasi_mahasiswa')
            ->first();

            $ipk = 0;
            $total_sks = 0;
            // $this->info($d->id_registrasi_mahasiswa);
            if($transkrip)
            {
                $ipk = $transkrip->ipk;
                $total_sks = $transkrip->total_sks;
            }

            $d->update([
                'feeder' => 0,
                'ipk' => $ipk,
                'sks_total' => $total_sks + $d->sks_semester,
            ]);

            $bar->advance();
        }
    }
}
