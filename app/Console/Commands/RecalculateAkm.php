<?php

namespace App\Console\Commands;

use App\Models\Perkuliahan\AktivitasKuliahMahasiswa;
use App\Models\Perkuliahan\TranskripMahasiswa;
use App\Models\SemesterAktif;
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

        $semester = SemesterAktif::first()->id_semester;

        $data = AktivitasKuliahMahasiswa::where('id_semester', $semester)->get();

        $totalData = $data->count();

        // progress bar
        $bar = $this->output->createProgressBar($totalData);

        foreach ($data as $d)
        {
            $transkrip = TranskripMahasiswa::select(
                'id_matkul', 'kode_mata_kuliah', 'nama_mata_kuliah','sks_mata_kuliah', 'nilai_angka', 'nilai_huruf','nilai_indeks'
            )
            ->where('id_registrasi_mahasiswa', $d->id_registrasi_mahasiswa)
            ->whereNotIn('nilai_huruf', ['F', ''])
            ->get();

            $totalSksTranskrip = $transkrip->sum('sks_mata_kuliah');
            $this->info($totalSksTranskrip);

            return;

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
