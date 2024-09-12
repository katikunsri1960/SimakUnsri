<?php

namespace App\Console\Commands;

use App\Models\Perkuliahan\KelasKuliah;
use App\Models\Perkuliahan\PesertaKelasKuliah;
use Illuminate\Console\Command;

class FixPeserta extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fix-peserta';

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

        $chunkSize = 8; // Define a chunk size for batch processing
        $totalData = KelasKuliah::where('id_semester', '20241')->count();

        $this->info("Total data: {$totalData}");

        KelasKuliah::where('id_semester', '20241')->chunk($chunkSize, function ($data, $page) use ($totalData, $chunkSize) {
            foreach ($data as $index => $d) {
                PesertaKelasKuliah::where('id_kelas_kuliah', $d->id_kelas_kuliah)->update(['feeder' => 0]);

                // Calculate and display progress
                $progress = (($page - 1) * $chunkSize + $index + 1) / $totalData * 100;
                $this->info("Progress: {$progress}% ({$d->id_kelas_kuliah}/{$totalData})");
            }
        });
    }
}
