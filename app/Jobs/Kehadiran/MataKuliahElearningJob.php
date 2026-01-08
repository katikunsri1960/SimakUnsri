<?php

namespace App\Jobs\Kehadiran;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Batchable;
use App\Models\mk_kelas;

class MataKuliahElearningJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $item;
    protected $idSemester;

    /**
     * Create a new job instance.
     * Terima data satuan dari Controller
     */
    public function __construct($item, $idSemester)
    {
        $this->item = $item;
        $this->idSemester = $idSemester;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Cek jika batch dibatalkan
        if ($this->batch()->cancelled()) {
            return;
        }

        $item = $this->item;
        $idSemester = $this->idSemester;

        if (!empty($item->kode_mata_kuliah) && !empty($item->nama_kelas_kuliah)) {
            mk_kelas::updateOrCreate(
                [
                    'kode_mata_kuliah' => "{$item->kode_mata_kuliah}-" . substr($idSemester, -3),
                    'nama_kelas_kuliah' => $item->nama_kelas_kuliah,
                ],
                [
                    'kelas_kuliah' => "{$item->kode_mata_kuliah}-{$item->nama_kelas_kuliah}",
                    'id_kelas_kuliah' => $item->id_kelas_kuliah,
                ]
            );
        }
    }
}