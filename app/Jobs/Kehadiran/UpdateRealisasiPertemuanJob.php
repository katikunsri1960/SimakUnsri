<?php

namespace App\Jobs\Kehadiran;

use Illuminate\Bus\Queueable;
use Illuminate\Bus\Batchable;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateRealisasiPertemuanJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    protected $dosenKelas;

    public function __construct(array $dosenKelas)
    {
        $this->dosenKelas = $dosenKelas;
    }

    public function handle()
    {
        foreach ($this->dosenKelas as $item) {
            if (empty($item['nip'])) continue;

            $jumlah_realisasi = DB::table('kehadiran_dosen')
                ->where('id_kelas_kuliah', $item['id_kelas_kuliah'])
                ->where('deskripsi_sesi', 'like', '%' . $item['nip'] . '%')
                ->count();

            DB::table('dosen_pengajar_kelas_kuliahs')
                ->where('id_dosen', $item['id_dosen'])
                ->where('id_kelas_kuliah', $item['id_kelas_kuliah'])
                ->update(['realisasi_minggu_pertemuan' => $jumlah_realisasi]);
        }
    }
}