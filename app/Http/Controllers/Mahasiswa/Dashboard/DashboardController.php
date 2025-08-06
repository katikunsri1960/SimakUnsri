<?php

namespace App\Http\Controllers\Mahasiswa\Dashboard;

use App\Models\Semester;
use Illuminate\Http\Request;
use App\Models\SemesterAktif;
use App\Models\Connection\Usept;
use App\Models\Connection\Tagihan;
use Illuminate\Support\Facades\DB;
use App\Models\Mahasiswa\Dashboard;
use App\Models\Perpus\BebasPustaka;
use Illuminate\Support\Facades\Bus;
use App\Http\Controllers\Controller;
use App\Models\Connection\Registrasi;
use App\Models\Connection\CourseUsept;
use App\Models\Mahasiswa\LulusDo;
use App\Models\Perkuliahan\ListKurikulum;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Perkuliahan\TranskripMahasiswa;
use App\Models\Perkuliahan\AktivitasKuliahMahasiswa;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        $status_keluar = LulusDo::where('id_registrasi_mahasiswa', $user->fk_id)
                ->select('id_jenis_keluar', 'nama_jenis_keluar', 'id_periode_keluar')
                ->first();

        $riwayat_pendidikan = RiwayatPendidikan::where('id_registrasi_mahasiswa', $user->fk_id)
                            ->first();
                            // dd($riwayat_pendidikan);

        if (empty($riwayat_pendidikan->id_jenis_keluar)) {
            $status_aktif = $status_keluar ? $status_keluar->nama_jenis_keluar : 'Aktif';
        } 

        // dd($status_aktif );

        $prodi_id = $riwayat_pendidikan->id_prodi;
        
        if($status_aktif == 'Aktif'){
            $semester_terakhir = SemesterAktif::leftJoin('semesters','semesters.id_semester','semester_aktifs.id_semester')
                        ->first();
        }else{
            $semester_terakhir = $status_keluar->id_periode_keluar;
        }
        // dd($semester_terakhir);
        

        $akm = AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa', $user->fk_id)
                        ->whereRaw("RIGHT(id_semester, 1) != 3")
                        ->orderBy('id_semester', 'DESC')
                        // ->limit(1)
                        ->first();

        $ips_sks_ipk = AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa', $user->fk_id)
                        ->whereRaw("RIGHT(id_semester, 1) != 3")
                        ->orderBy('id_semester', 'ASC')
                        // ->limit(1)
                        ->get();
        
        $semester = Semester::orderBy('id_semester', 'ASC')
                        ->whereBetween('id_semester', [$riwayat_pendidikan->id_periode_masuk, $semester_terakhir])
                        ->whereRaw('RIGHT(id_semester, 1) != ?', [3])
                        ->get();

        $semester_ke = $semester->count();

        $transkrip = TranskripMahasiswa::select(
                DB::raw('SUM(CAST(sks_mata_kuliah AS UNSIGNED)) as total_sks'), // Mengambil total SKS tanpa nilai desimal
                DB::raw('ROUND(SUM(nilai_indeks * sks_mata_kuliah) / SUM(sks_mata_kuliah), 2) as ipk') // Mengambil IPK dengan 2 angka di belakang koma
                )
                ->where('id_registrasi_mahasiswa', $user->fk_id)
                ->whereNotIn('nilai_huruf', ['F', ''])
                ->groupBy('id_registrasi_mahasiswa')
                ->first();
                // dd($akm);

        $nilai_usept_prodi = ListKurikulum::where('id_kurikulum', $riwayat_pendidikan->id_kurikulum)->first();

        try {
            set_time_limit(10);

            $nilai_usept_mhs = Usept::whereIn('nim', [$riwayat_pendidikan->nim, $riwayat_pendidikan->biodata->nik])->pluck('score');
            $nilai_course = CourseUsept::whereIn('nim', [$riwayat_pendidikan->nim, $riwayat_pendidikan->biodata->nik])->get()->pluck('konversi');

            $all_scores = $nilai_usept_mhs->merge($nilai_course);
            $usept = $all_scores->max();

            if ($nilai_usept_prodi) {
                $usept_data = [
                    'score' => $usept,
                    'class' => $usept < $nilai_usept_prodi->nilai_usept ? 'danger' : 'success',
                    'status' => $usept < $nilai_usept_prodi->nilai_usept ? 'Tidak memenuhi Syarat' : 'Memenuhi Syarat',
                ];
            }else{
                $usept_data = [
                    'score' => '',
                    'class' => 'danger',
                    'status' => 'Kurikulum Belum diatur',
                ];
            }

        } catch (\Throwable $th) {
            //throw $th;
            $usept_data = [
                'score' => 0,
                'class' => 'danger',
                'status' => 'Database USEPT tidak bisa diakses, silahkan hubungi pengelola USEPT.',
            ];
        }

        $bebas_pustaka = BebasPustaka::where('id_registrasi_mahasiswa', $riwayat_pendidikan->id_registrasi_mahasiswa)->first();
        // dd($bebas_pustaka, $usept_data);
        return view('mahasiswa.dashboard', compact(
            'riwayat_pendidikan', 'semester_ke', 'akm','transkrip','ips_sks_ipk', 
            'usept_data', 'bebas_pustaka', 'status_aktif'
        ));
    }

    public function check_sync(Request $request)
    {
        $id_batch = $request->id_batch;
        $batching = Bus::findBatch($id_batch);

        return [
            'total' => $batching->totalJobs,
            'job_processed' => $batching->processedJobs(),
            'job_pending' => $batching->pendingJobs,
            'progress' => $batching->progress(),
        ];

    }
}
