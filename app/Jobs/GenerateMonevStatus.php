<?php

namespace App\Jobs;

use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Monitoring\MonevStatusMahasiswa;
use App\Models\Monitoring\MonevStatusMahasiswaDetail;
use App\Models\Semester;
use App\Models\SemesterAktif;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateMonevStatus implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $prodi;

    protected $semesters, $semester;
    /**
     * Create a new job instance.
     */
    public function __construct($prodi)
    {

        $this->prodi = $prodi;

        $this->semester = SemesterAktif::first()->id_semester;
        $this->semesters = Semester::orderBy('id_semester', 'ASC')
                        ->whereNot('semester', 3)
                        ->get();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $this->lewat_semester();

    }

    private function hitung_semester($semester_masuk, $semester_sekarang)
    {
        $total_semester = $this->semesters
            ->whereBetween('id_semester', [$semester_masuk, $semester_sekarang])
            ->count();

        return $total_semester;
    }

    public function lewat_semester()
    {

        $jenjang = [
            22 => ['nama' => 'D3', 'max_semester' => 10, 'min_semester_down' => 2, 'min_sks_down' => 27, 'min_semester_up' => 8, 'min_sks_up' => 108, 'min_ipk' => 2],
            30 => ['nama' => 'S1', 'max_semester' => 14, 'min_semester_down' => 4, 'min_sks_down' => 52, 'min_semester_up' => 10, 'min_sks_up' => 108, 'min_ipk' => 2],
            35 => ['nama' => 'S2', 'max_semester' => 8, 'min_semester_down' => 2, 'min_sks_down' => 18],
            40 => ['nama' => 'S3', 'max_semester' => 14],
            31 => ['nama' => 'Profesi', 'max_semester' => 10],
            32 => ['nama' => 'Sp-1', 'max_semester' => 16],
            37 => ['nama' => 'Sp-2', 'max_semester' => 12],
        ];

        // $riwayat = RiwayatPendidikan::with('prodi')
        //     ->withSum('transkrip_mahasiswa', 'sks_mata_kuliah')
        //     ->whereNull('id_jenis_keluar')
        //     ->get();

        $riwayat = RiwayatPendidikan::
            whereNull('id_jenis_keluar')
            ->where('id_prodi', $this->prodi)
            ->select('id_registrasi_mahasiswa', 'id_prodi', 'id_periode_masuk')
            ->with('prodi','lulus_do')
            ->whereHas('lulus_do', function ($query) {
                $query->whereNull('id_registrasi_mahasiswa');
            })
            ->withSum('transkrip_mahasiswa as total_sks', 'sks_mata_kuliah')
            ->get();

        $monev = MonevStatusMahasiswa::firstOrCreate([
            'id_prodi' => $this->prodi,
            'id_semester' => $this->semester,
        ]);

        $removeDetail = MonevStatusMahasiswaDetail::where('monev_status_mahasiswa_id', $monev->id)
            // ->where('status', 'mahasiswa_lewat_semester')
            ->delete();

        $monevDetail = [];

        $countLewatSemester = 0;

        $countLewat10Semester = 0;

        foreach ($riwayat as $r) {
            $semester_masuk = $r->id_periode_masuk;
            $semester_sekarang = $this->semester;
            $id_jenjang = $r->prodi->id_jenjang_pendidikan;

            if (isset($jenjang[$id_jenjang])) {
                $total_semester = $this->hitung_semester($semester_masuk, $semester_sekarang);
                $max_semester = $jenjang[$id_jenjang]['max_semester'];

                if ($total_semester > $max_semester) {

                    $monevDetail[] = [
                        'monev_status_mahasiswa_id' => $monev->id,
                        'status' => 'mahasiswa_lewat_semester',
                        'id_registrasi_mahasiswa' => $r->id_registrasi_mahasiswa,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    $countLewatSemester++;
                }


                if ($total_semester > 10 && $id_jenjang == 30) {
                    $monevDetail[] = [
                        'monev_status_mahasiswa_id' => $monev->id,
                        'status' => 'lewat_10_semester',
                        'id_registrasi_mahasiswa' => $r->id_registrasi_mahasiswa,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    $countLewat10Semester++;
                }
            }
        }

        $monev->update([
            'mahasiswa_lewat_semester' => $countLewatSemester,
            'lewat_10_semester' => $countLewat10Semester,
            'updated_at' => now(),
            'created_at' => now(),
        ]);

        if (count($monevDetail) > 0) {
            MonevStatusMahasiswaDetail::insert($monevDetail);
        }

        return true;

    }

    public function do_under_4()
    {
        // Syarat DO

        // D3
        // 1. Pada semester 3 < 27 sks
        // 2. pada semester 3 kredit >= 27 sks dan IPK < 2.00

    }
}
