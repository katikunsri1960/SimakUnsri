<?php

namespace App\Http\Controllers\Mahasiswa\Kelulusan;


use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use App\Models\Wisuda;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use App\Models\PeriodeWisuda;
use App\Models\SemesterAktif;
use App\Models\AsistensiAkhir;
use App\Models\ProfilPt;
use App\Models\WisudaChecklist;
use App\Models\WisudaSyaratAdm;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Referensi\AllPt;
use App\Models\Connection\Usept;
use Illuminate\Cache\Repository;
use App\Models\BeasiswaMahasiswa;
use App\Models\Connection\Tagihan;
use Illuminate\Support\Facades\DB;
use App\Models\Perpus\BebasPustaka;
use App\Http\Controllers\Controller;
use App\Models\Connection\Registrasi;
use App\Models\Connection\CourseUsept;
use App\Models\Mahasiswa\BiodataMahasiswa;
use App\Models\Mahasiswa\PengajuanCuti;
use App\Models\Perkuliahan\ListKurikulum;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Perkuliahan\BimbingMahasiswa;
use App\Models\Perkuliahan\AktivitasMahasiswa;
use App\Models\Perkuliahan\AktivitasKuliahMahasiswa;
use App\Models\Perkuliahan\TranskripMahasiswa;
use Illuminate\Support\Facades\Storage;
use App\Models\BkuProgramStudi;
use App\Models\SKPI;
use App\Models\SKPIJenisKegiatan;
use App\Models\SKPIBidangKegiatan;

class EligibleController extends Controller
{
    public function index(Request $request)
    {
        $id_reg = auth()->user()->fk_id;

        $semester_aktif = SemesterAktif::first();

        $riwayat_pendidikan = RiwayatPendidikan::with('prodi', 'prodi.fakultas', 'prodi.jurusan', 'lulus_do', 'biodata')
                    ->where('id_registrasi_mahasiswa', $id_reg)
                    ->first();

        // $status_keluar = $riwayat_pendidikan->lulus_do->

        // dd($riwayat_pendidikan);

        $aktivitas_kuliah = AktivitasKuliahMahasiswa::with('pembiayaan')->where('id_registrasi_mahasiswa', $id_reg)
                ->where('id_semester', $semester_aktif->id_semester)
                ->orderBy('id_semester', 'desc')
                ->first();

        if (!$aktivitas_kuliah) {
            $aktivitas_kuliah = AktivitasKuliahMahasiswa::with('pembiayaan')->where('id_registrasi_mahasiswa', $id_reg)
                ->orderBy('id_semester', 'desc')
                ->first();
        }

        // dd($aktivitas_kuliah);

        $kurikulum = ListKurikulum::where('id_kurikulum', $riwayat_pendidikan->id_kurikulum)->first();

        if (!$kurikulum) {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Kurikulum Anda tidak ditemukan, Silahkan hubungi Koor. Prodi!');
        }

        // 1 SYARAT SKS LULUS
        // if ($aktivitas_kuliah->sks_total < $kurikulum->jumlah_sks_lulus) {
        //     return redirect()->route('mahasiswa.dashboard')->with('error', 'Anda tidak dapat melakukan pendaftaran wisuda, Silahkan selesaikan minimal '.$kurikulum->jumlah_sks_lulus.' sks!');
        // }

        $aktivitas = AktivitasMahasiswa::with(['anggota_aktivitas_personal', 'bimbing_mahasiswa', 'nilai_konversi'])
                ->whereHas('bimbing_mahasiswa', function ($query) {
                    $query->whereNotNull('id_bimbing_mahasiswa');
                })
                ->whereHas('anggota_aktivitas_personal', function ($query) use ($riwayat_pendidikan) {
                    $query->where('id_registrasi_mahasiswa', $riwayat_pendidikan->id_registrasi_mahasiswa)
                        ->where('nim', $riwayat_pendidikan->nim);
                })
                ->whereHas('nilai_konversi', function ($query) {
                    $query->where('nilai_indeks', '>', 0.00);
                })
                // ->where('id_semester', $semester_aktif)
                ->where('id_prodi', $riwayat_pendidikan->id_prodi)
                ->whereIn('id_jenis_aktivitas', ['1', '3', '4', '22'])
                ->first();

        // if()
        // dd($aktivitas);

        // 2 SYARAT AKTIVITAS
        // if (!$aktivitas) {
        //     return redirect()->route('mahasiswa.dashboard')->with('error', 'Anda tidak dapat melakukan pendaftaran wisuda, Silahkan selesaikan Aktivitas Tugas Akhir!');
        // }

        // $wisuda = Wisuda::with('bku_prodi')
        //         ->where('id_registrasi_mahasiswa', $id_reg)
        //             ->whereHas('periode_wisuda', function($q){
        //                 $q->where('is_active', 1);
        //             })
        //             ->with([
        //                 'aktivitas_mahasiswa',
        //                 'periode_wisuda' => function ($q) {
        //                     $q->where('is_active', 1);
        //                 }
        //             ])
        //             ->first();

        $wisuda = Wisuda::leftJoin('pisn_mahasiswas as pisn', 'pisn.id_registrasi_mahasiswa', 'data_wisuda.id_registrasi_mahasiswa')
                ->select('data_wisuda.*', 'pisn.penomoran_ijazah_nasional as pisn')
                ->where('data_wisuda.id_registrasi_mahasiswa', $id_reg)
                ->first();

                // dd($wisuda);
        // 3 SYARAT STATUS KELUAR 
        // if (!$riwayat_pendidikan->lulus_do && $riwayat_pendidikan->id_jenis_keluar != 1) {
        //     return redirect()->route('mahasiswa.dashboard')->with('error', 'Anda tidak diizinkan mengakses halaman wisuda, Anda masih berstatus Mahasiswa Aktif!');
        // }

        // 4 SYARAT STATUS KELUAR TAPI BELUM WISUDA
        // if ($riwayat_pendidikan->lulus_do && $riwayat_pendidikan->lulus_do->id_jenis_keluar != 1 && $wisuda == null) {
        //     return redirect()->route('mahasiswa.dashboard')->with('error', 'Anda tidak diizinkan mengakses halaman wisuda, status mahasiswa Anda adalah '.$riwayat_pendidikan->lulus_do->nama_jenis_keluar.'!');
        // }

        $bebas_pustaka = BebasPustaka::where('id_registrasi_mahasiswa', $id_reg)->first();

        // 5 SYARAT BEBAS PUSTAKA
        // if (!$bebas_pustaka) {
        //     return redirect()
        //         ->route('mahasiswa.dashboard')
        //         ->with('error', 'Anda belum melakukan upload repository dan bebas pustaka, silakan menghubungi Admin Perpustakaan.');
        // }

        // 6 SYARAT BEBAS PUSTAKA TAPI BELUM WISUDA
        // if(!$wisuda){
        //     if (empty($bebas_pustaka->file_bebas_pustaka)) {
        //         return redirect()
        //             ->route('mahasiswa.dashboard')
        //             ->with('error', 'File bebas pustaka belum diupload, silakan menghubungi Admin Perpustakaan.');
        //     }

        //     if (empty($bebas_pustaka->link_repo)) {
        //         return redirect()
        //             ->route('mahasiswa.dashboard')
        //             ->with('error', 'Link repository belum diisi, silakan menghubungi Admin Perpustakaan.');
        //     }
        // }
        
        $skpi_data = SKPI::leftJoin('skpi_jenis_kegiatan', 'skpi_jenis_kegiatan.id', 'skpi_data.id_jenis_skpi')
                    ->select('skpi_data.*', 'skpi_jenis_kegiatan.bidang_id', 'skpi_jenis_kegiatan.kriteria')
                    ->where('skpi_data.id_registrasi_mahasiswa', $id_reg)
                    ->get();

        if(!$riwayat_pendidikan->id_kurikulum ) {
            return redirect()->route('mahasiswa.wisuda.index')->with('error', 'Kurikulum Belum diatur, Silahkan hubungi Koor. Prodi!');
        }else{
            $nilai_usept_prodi = ListKurikulum::where('id_kurikulum', $riwayat_pendidikan->id_kurikulum)->first();
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

        } catch (\Throwable $th) {
            //throw $th;
            $useptData = [
                'score' => 0,
                'class' => 'danger',
                'status' => 'Database USEPT tidak bisa diakses, silahkan hubungi pengelola USEPT.',
            ];
        }

       // dd($useptData);

        return view('mahasiswa.kelulusan.eligible.index', [
            'aktivitas' => $aktivitas,
            'aktivitas_kuliah' => $aktivitas_kuliah,
            'wisuda' => $wisuda,
            'bebas_pustaka' => $bebas_pustaka,
            'usept' => $useptData,
            'riwayat_pendidikan' => $riwayat_pendidikan,
            'kurikulum' => $kurikulum,
            'skpi_data' => $skpi_data
        ]);
    }
}
