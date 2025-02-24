<?php

namespace App\Http\Controllers\Fakultas\LainLain;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use App\Models\Wisuda;
use Illuminate\Http\Request;
use App\Models\SemesterAktif;
use App\Models\AsistensiAkhir;
use App\Models\Referensi\AllPt;
use App\Models\Connection\Usept;
use Illuminate\Cache\Repository;
use App\Models\BeasiswaMahasiswa;
use App\Models\Connection\Tagihan;
use App\Models\Perpus\BebasPustaka;
use App\Http\Controllers\Controller;
use App\Models\Connection\Registrasi;
use App\Models\Connection\CourseUsept;
use App\Models\Mahasiswa\PengajuanCuti;
use App\Models\Perkuliahan\ListKurikulum;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Perkuliahan\BimbingMahasiswa;
use App\Models\Perkuliahan\AktivitasMahasiswa;
use App\Models\Perkuliahan\AktivitasKuliahMahasiswa;

class WisudaController extends Controller
{
    public function index(Request $request)
    {
        $id_fak = auth()->user()->fk_id;

        $semester_aktif = SemesterAktif::first();

        $data = Wisuda::with('prodi', 'prodi.fakultas', 'prodi.jurusan', 'riwayat_pendidikan')
                ->whereHas('prodi', function ($query) use ($id_fak) {
                    $query->where('fakultas_id', $id_fak);
                })
                ->get();

        foreach ($data as $d) {
            $riwayat_pendidikan = RiwayatPendidikan::where('id_registrasi_mahasiswa', $d->id_registrasi_mahasiswa)->first();

            $bebas_pustaka = BebasPustaka::where('id_registrasi_mahasiswa', $d->id_registrasi_mahasiswa)->first();

            if(!$d->riwayat_pendidikan->id_kurikulum ) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Kurikulum Mahasiswa Belum Diatur!!',
                ]);
            }else{
                $nilai_usept_prodi = ListKurikulum::where('id_kurikulum', $d->riwayat_pendidikan->id_kurikulum)->first();
            }

            try {
                set_time_limit(10);

                $nilai_usept_mhs = Usept::whereIn('nim', [$riwayat_pendidikan->nim, $riwayat_pendidikan->biodata->nik])->pluck('score');
                $nilai_course = CourseUsept::whereIn('nim', [$riwayat_pendidikan->nim, $riwayat_pendidikan->biodata->nik])->get()->pluck('konversi');

                $all_scores = $nilai_usept_mhs->merge($nilai_course);
                $usept = $all_scores->max();

                $useptData = [
                    'score' => $usept,
                    'class' => $usept < $nilai_usept_prodi->nilai_usept ? 'danger' : 'success',
                    'status' => $usept < $nilai_usept_prodi->nilai_usept ? 'Tidak memenuhi Syarat' : 'Memenuhi Syarat',
            ];

            } catch (\Throwable) {
                $useptData = [
                    'score' => 0,
                    'class' => 'danger',
                    'status' => 'Database USEPT tidak bisa diakses, silahkan hubungi pengelola USEPT.',
                ];
            }

            $d->bebas_pustaka = $bebas_pustaka;
            $d->useptData = $useptData;
        }
        // dd($data, $useptData);

        return view('fakultas.lain-lain.wisuda.index', [
            'data' => $data,
            // 'bebas_pustaka' => $bebas_pustaka,
            // 'useptData' => $useptData,
        ]);
    }

    public function approve(Request $request, Wisuda $wisuda)
    {
        $bebas_pustaka = BebasPustaka::where('id_registrasi_mahasiswa', $wisuda->id_registrasi_mahasiswa)->first();

        if(!$wisuda->riwayat_pendidikan->id_kurikulum ) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kurikulum Mahasiswa Belum Diatur!!',
            ]);
        }else{
            $nilai_usept_prodi = ListKurikulum::where('id_kurikulum', $wisuda->riwayat_pendidikan->id_kurikulum)->first();
        }

        try {
            set_time_limit(10);

            $riwayat_pendidikan = RiwayatPendidikan::where('id_registrasi_mahasiswa', $wisuda->id_registrasi_mahasiswa)->first();
            $nilai_usept_mhs = Usept::whereIn('nim', [$riwayat_pendidikan->nim, $riwayat_pendidikan->biodata->nik])->pluck('score');
            $nilai_course = CourseUsept::whereIn('nim', [$riwayat_pendidikan->nim, $riwayat_pendidikan->biodata->nik])->get()->pluck('konversi');

            $all_scores = $nilai_usept_mhs->merge($nilai_course);
            $usept = $all_scores->max();

            $useptData = [
                'score' => $usept,
                'class' => $usept < $nilai_usept_prodi->nilai_usept ? 'danger' : 'success',
                'status' => $usept < $nilai_usept_prodi->nilai_usept ? 'Tidak memenuhi Syarat' : 'Memenuhi Syarat',
            ];

        } catch (\Throwable) {
            $useptData = [
                'score' => 0,
                'class' => 'danger',
                'status' => 'Database USEPT tidak bisa diakses, silahkan hubungi pengelola USEPT.',
            ];
        }

        // dd($bebas_pustaka, $useptData);

        if (!$bebas_pustaka || $useptData['status'] == 'Tidak memenuhi Syarat') {
            return redirect()->back()->with('error', 'Mahasiswa belum memenuhi syarat bebas pustaka atau USEPT.');
        }

        $lama_studi = Carbon::parse($wisuda->tgl_keluar)->diffInMonths(Carbon::parse($wisuda->tgl_masuk));
        // dd($lama_studi, $wisuda);

        $wisuda->update([
            'lama_studi'=> $lama_studi,
            'approved' => 2,
            'tgl_keluar' => $request->tgl_sk_yudisium,
            'no_sk_yudisium' => $request->no_sk_yudisium,
            'tgl_sk_yudisium' => $request->tgl_sk_yudisium,
        ]);
        
        return redirect()->back()->with('success', 'Pendaftaran Wisuda berhasil disetujui.');
    }

    public function decline(Request $request, $id)
    {
        $wisuda = Wisuda::findOrFail($id);

        $wisuda->update([
            'approved' => 98,
            'alasan_pembatalan' => $request->alasan_pembatalan ?? null,
        ]);

        return redirect()->back()->with('success', 'Pendaftaran Wisuda berhasil dibatalkan.');
    }

}
