<?php

namespace App\Console\Commands;

use App\Models\Perkuliahan\DosenPengajarKelasKuliah;
use App\Models\Perkuliahan\KelasKuliah;
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
                ->select('dosen_pengajar_kelas_kuliahs.*', 'm.sks_mata_kuliah as total_sks', 'm.kode_mata_kuliah as kode_mk')
                ->get();

        $groupedData = $data->groupBy('id_kelas_kuliah');
        $this->info($groupedData->count());
        $progressBar = $this->output->createProgressBar($groupedData->count());
        $jumlahKelas = 0;
        $file = fopen(public_path('rencana_dosen.csv'), 'a');
        if (ftell($file) == 0) {
            fputcsv($file, ['KodeMk', 'Nama Kelas', 'jenjang', 'prodi']);
        }

        foreach ($groupedData as $id_kelas_kuliah => $group) {
            $data = [];
            $sksCheck = 0;

            $jumlahPertemuan = $group->sum('rencana_minggu_pertemuan');
            $count = $group->count();

            if ($jumlahPertemuan > 16 && $count > 1) {
                $jumlahKelas++;
                $dbKelas = KelasKuliah::where('id_kelas_kuliah', $id_kelas_kuliah)
                ->leftJoin('program_studis as prodi', 'prodi.id_prodi', 'kelas_kuliahs.id_prodi')
                ->select('kelas_kuliahs.nama_kelas_kuliah', 'prodi.nama_jenjang_pendidikan', 'prodi.nama_program_studi')->first();
                // tulis data ke dalam file txt di folder public

                fputcsv($file, [$group->first()->kode_mk, $dbKelas->nama_kelas_kuliah, $dbKelas->nama_jenjang_pendidikan, $dbKelas->nama_program_studi]);


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
                    $data[$minUrutanKey]['rencana_minggu_pertemuan'] += round($rencana_minggu_pertemuan - 16, 0);
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
        fclose($file);

        $this->info("Jumlah kelas: {$jumlahKelas}");
    }
}
