<?php

namespace App\Jobs\Kehadiran;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use App\Models\mk_kelas;
use App\Models\SemesterAktif;
use Illuminate\Bus\Batchable;


class MataKuliahElearningJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
         $idSemester = SemesterAktif::latest()->value('id_semester');

        $semesteraktif = DB::table('semester_aktifs')
            ->join('kelas_kuliahs', 'semester_aktifs.id_semester', '=', 'kelas_kuliahs.id_semester')
            ->join('matkul_kurikulums', 'kelas_kuliahs.id_matkul', '=', 'matkul_kurikulums.id_matkul')
            ->where('semester_aktifs.id_semester', $idSemester)
            ->select('matkul_kurikulums.kode_mata_kuliah', 'kelas_kuliahs.nama_kelas_kuliah','kelas_kuliahs.id_kelas_kuliah')
            ->get();

        foreach ($semesteraktif as $items) {
            if (!empty($items->kode_mata_kuliah) && !empty($items->nama_kelas_kuliah)) {
                mk_kelas::updateOrCreate(
                    [
                        'kode_mata_kuliah' => "{$items->kode_mata_kuliah}-" . substr($idSemester, -3),
                        'nama_kelas_kuliah' => $items->nama_kelas_kuliah,
                    ],
                    [
                        'kelas_kuliah' => "{$items->kode_mata_kuliah}-{$items->nama_kelas_kuliah}",
                        'id_kelas_kuliah' => $items->id_kelas_kuliah,
                    ]
                );
            }
        }
    }
}
