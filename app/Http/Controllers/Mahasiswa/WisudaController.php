<?php

namespace App\Http\Controllers\Mahasiswa;

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

class WisudaController extends Controller
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

        return view('mahasiswa.wisuda.index', [
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

    public function get_kecamatan(Request $request)
    {
        $db = new Wilayah();

        $data = $db->with('kab_kota')
                    ->where('nama_wilayah', 'like', '%'.$request->q.'%')
                    ->where('id_level_wilayah', 3)
                    ->orderBy('id', 'desc')->get();

        return response()->json($data);
    }


    //DATA INDUK
    public function data_induk()
    {
        // dd($semester_aktif->id_semester);
        $id_reg = auth()->user()->fk_id;

        $riwayat_pendidikan = RiwayatPendidikan::with('biodata','lulus_do','prodi', 'prodi.fakultas', 'prodi.jurusan', 'biodata.wilayah.kab_kota')->where('id_registrasi_mahasiswa', $id_reg)->first();

        $semester_aktif=SemesterAktif::with('semester')->first();

        $kecamatan = Wilayah::with('level', 'kab_kota')
                ->where('id_level_wilayah', 3)
                ->where('id_wilayah', $riwayat_pendidikan->biodata->id_wilayah)
                ->first();
// dd($kecamatan);
        $today = Carbon::now()->toDateString();

        $wisuda = Wisuda::where('id_registrasi_mahasiswa', $id_reg)->first();

        $wisuda_ke = PeriodeWisuda::where('tanggal_mulai_daftar', '<=', $today)
                    ->where('tanggal_akhir_daftar', '>=', $today)
                    ->where('is_active', '1')
                    ->first();
        
        if(!$wisuda_ke) {
            return redirect()->back()->with('error',
                'Tidak ada periode Wisuda yang tersedia !!');
        }

        // Cek apakah file berhasil diupload
        // if ($wisuda) {
        //     return redirect()->back()->with('error', 'Anda telah melakukan pendaftaran wisuda !!');
        // }

        if(!$riwayat_pendidikan->id_kurikulum ) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kurikulum Mahasiswa Belum Diatur!!',
            ]);
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

        if($useptData['class'] == 'danger') {
            return redirect()->back()->with('error', 'Nilai USEPT Anda belum memenuhi syarat, Silahkan lakukan ujian ulang!');
        }

        $bebas_pustaka = BebasPustaka::where('id_registrasi_mahasiswa', $id_reg)->first();

        // dd($riwayat_pendidikan->lulus_do, $wisuda, $riwayat_pendidikan->id_jenis_keluar);
        
        if ($riwayat_pendidikan->lulus_do) {
            return redirect()->back()->with('error','Anda tidak diizinkan mengakses halaman wisuda, status mahasiswa Anda adalah '. $riwayat_pendidikan->lulus_do->nama_jenis_keluar . '!');
        }elseif (!empty($riwayat_pendidikan->id_jenis_keluar)) {
            return redirect()->back()->with('error','Anda tidak diizinkan mengakses halaman wisuda, status mahasiswa Anda adalah '. $riwayat_pendidikan->keterangan_keluar . '!');
        }

        if (!$bebas_pustaka) {
            return redirect()->back()->with('error', 'Anda belum melakukan upload repository dan bebas pustaka, silakan menghubungi Admin Perpustakaan.');
        }

        if (empty($bebas_pustaka->file_bebas_pustaka)) {
            return redirect()->back()->with('error', 'File bebas pustaka belum diupload, silakan menghubungi Admin Perpustakaan.');
        }

        if (empty($bebas_pustaka->link_repo)) {
            return redirect()->back()->with('error', 'Link repository belum diisi, silakan menghubungi Admin Perpustakaan.');
        }

        try {
            $asal_sekolah = Registrasi::leftJoin('data_sekolah', 'data_sekolah.npsn', 'reg_master.rm_pddk_slta_kode')
                    ->select('reg_master.rm_pddk_sd_nama','reg_master.rm_pddk_sd_lokasi', 'reg_master.rm_pddk_sd_thn_lulus',
                            'reg_master.rm_pddk_sltp_nama', 'reg_master.rm_pddk_sltp_lokasi', 'reg_master.rm_pddk_sltp_thn_lulus',
                            'data_sekolah.nama_sekolah', 'reg_master.rm_pddk_slta_jurusan', 'reg_master.rm_pddk_slta_thn_lulus',
                            'data_sekolah.nama_kabupaten', 'data_sekolah.nama_provinsi', 'data_sekolah.akreditasi_sekolah')
                    ->where('rm_nim', $riwayat_pendidikan->nim)
                    ->first();
        } catch (\Exception $e) {

            // log error
            \Log::error('Koneksi database gagal: '.$e->getMessage());

            // nilai default jika gagal
            $asal_sekolah = null;
        }

        return view('mahasiswa.wisuda.pendaftaran.data-induk', ['riwayat_pendidikan' => $riwayat_pendidikan, 'semester_aktif' => $semester_aktif, 'kecamatan'=> $kecamatan,
                    'usept' => $useptData, 'bebas_pustaka' => $bebas_pustaka, 'asal_sekolah' => $asal_sekolah, 'wisuda' => $wisuda]);
    }

    public function data_induk_store(Request $request)
    {
        // dd($request->all());
        $perguruan_tinggi = ProfilPt::first();
        $id_reg = auth()->user()->fk_id;

        $riwayat_pendidikan = RiwayatPendidikan::with('biodata')
            ->where('id_registrasi_mahasiswa', $id_reg)
            ->first();

        $wisuda = Wisuda::where('id_registrasi_mahasiswa', $id_reg)->first();

        $request->validate([
            'no_hp_ayah' => 'required|regex:/^[0-9]+$/',
            'no_hp_ibu' => 'required|regex:/^[0-9]+$/',
            'nama_ayah' => 'required|regex:/^[a-zA-Z\s]+$/',
            'nik' => 'required',
            'id_wilayah' => 'required',
            'ijazah_terakhir_file' => [
                $wisuda && $wisuda->ijazah_terakhir_file ? 'nullable' : 'required',
                'file',
                'mimes:pdf',
                'max:512' // 500 KB
            ],
            'alamat_orang_tua' => 'required',
        ]);

        
        if ($request->id_wilayah) {
            $wilayah = Wilayah::where('id_wilayah', $request->id_wilayah)->first();
        } else {
            $wilayah = Wilayah::where('id_wilayah', $riwayat_pendidikan->biodata->id_wilayah)->first();
        }

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

                // dd($aktivitas);
        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | HANDLE FILE IJAZAH
            |--------------------------------------------------------------------------
            */

            $ijazah_terakhir_file = $wisuda->ijazah_terakhir_file ?? null;

            if ($request->hasFile('ijazah_terakhir_file')) {

                $file = $request->file('ijazah_terakhir_file');

                $ijazahName = 'ijazah_terakhir_' . str_replace(' ', '_', $riwayat_pendidikan->nim) . '.' . $file->getClientOriginalExtension();

                $ijazahPath = $file->storeAs('wisuda/ijazah', $ijazahName, 'public');

                if (!$ijazahPath) {
                    return redirect()->back()->with('error', 'File ijazah terakhir gagal diunggah.');
                }

                $ijazah_terakhir_file = 'storage/' . $ijazahPath;
            }

            /*
            |--------------------------------------------------------------------------
            | SIMPAN DATA WISUDA
            |--------------------------------------------------------------------------
            */

            if ($wisuda) {

                $wisuda->update([
                    'ijazah_terakhir_file' => $ijazah_terakhir_file,
                    'verified_induk' => 1,
                ]);

            } else {

                $wisuda = Wisuda::updateOrCreate(
                    ['id_registrasi_mahasiswa' => $id_reg],
                    [
                        'id_perguruan_tinggi' => $perguruan_tinggi->id_perguruan_tinggi,
                        'id_prodi' => $riwayat_pendidikan->id_prodi,
                        'tgl_masuk' => $riwayat_pendidikan->tanggal_daftar,
                        'nim' => $riwayat_pendidikan->nim,
                        'id_aktivitas' => $aktivitas->id_aktivitas ?? null,
                        'nama_mahasiswa' => $riwayat_pendidikan->nama_mahasiswa,
                        'ijazah_terakhir_file' => $ijazah_terakhir_file,
                        'approved' => 0,
                        'verified_induk' => 1,
                        'verified_akademik' => 0,
                        'verified_ta' => 0,
                        'verified_wisuda' => 0,
                        'verified_skpi' => 0,
                        'finalisasi_data' => 0,
                    ]
                );
            }

            /*
            |--------------------------------------------------------------------------
            | UPDATE BIODATA
            |--------------------------------------------------------------------------
            */

            BiodataMahasiswa::where('id_mahasiswa', $riwayat_pendidikan->id_mahasiswa)
                ->update([
                    'feeder' => 0,
                    'nik' => $request->nik,
                    'jalan' => $request->jalan,
                    'dusun' => $request->dusun,
                    'rt' => $request->rt,
                    'rw' => $request->rw,
                    'kelurahan' => $request->kelurahan,
                    'kode_pos' => $request->kode_pos,
                    'id_wilayah' => $wilayah->id_wilayah,
                    'nama_wilayah' => $wilayah->nama_wilayah,
                    'handphone' => $request->handphone,
                    'email' => $request->email,
                    'nama_ayah' => strtoupper($request->nama_ayah),
                    'no_hp_ayah' => $request->no_hp_ayah,
                    'no_hp_ibu' => $request->no_hp_ibu,
                    'alamat_orang_tua' => $request->alamat_orang_tua,
                ]);

            DB::commit();

            return redirect()
                ->route('mahasiswa.wisuda.pendaftaran.data-akademik')
                ->with('success', 'Data Induk berhasil disimpan, silakan lanjut ke Data Akademik!');

        } catch (\Exception $e) {

            DB::rollBack();

            \Log::error('Wisuda store error: '.$e->getMessage());

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data!');
        }
    }

    //DATA AKADEMIK
    public function data_akademik()
    {       
        $id_reg = auth()->user()->fk_id;

        $riwayat_pendidikan = RiwayatPendidikan::with('biodata','lulus_do','prodi', 'prodi.fakultas', 'prodi.jurusan', 'biodata.wilayah.kab_kota')->where('id_registrasi_mahasiswa', $id_reg)->first();

        $semester_aktif=SemesterAktif::with('semester')->first();

        $today = Carbon::now()->toDateString();

        $wisuda = Wisuda::where('id_registrasi_mahasiswa', $id_reg)->first();

        // Cek apakah file berhasil diupload
        // if ($wisuda) {
        //     return redirect()->back()->with('error', 'Anda telah melakukan pendaftaran wisuda !!');
        // }

        $aktivitas_kuliah = AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa', $id_reg)
                // ->where('id_semester', $semester_aktif->id_semester)
                ->orderBy('id_semester', 'ASC')
                ->get();
        
        //DATA TRANSKRIP MAHASISWA
        $jobData =  DB::table('job_batches')->where('name', 'transkrip-mahasiswa')->where('pending_jobs', '>', 0)->first();

        $statusSync = $jobData ? 1 : 0;

        $id_batch = $jobData ? $jobData->id : null;
        
        $transkrip_mahasiswa=TranskripMahasiswa::where('id_registrasi_mahasiswa',$id_reg)->orderBy('nama_mata_kuliah','asc')->get();

        $total_sks_transkrip = $transkrip_mahasiswa ->whereNotNull('nilai_indeks')->sum('sks_mata_kuliah');

        $bobot = 0;
        
        foreach ($transkrip_mahasiswa as $t) {
            $bobot += $t->nilai_indeks * $t->sks_mata_kuliah;
        }

        
        if($total_sks_transkrip != 0){
            $ipk = number_format($bobot / $total_sks_transkrip, 2);
        }else{
            $ipk=0;
        }

        return view('mahasiswa.wisuda.pendaftaran.data-akademik', [
                    'riwayat_pendidikan' => $riwayat_pendidikan, 'semester_aktif' => $semester_aktif, 'wisuda' => $wisuda,
                    'aktivitas_kuliah' => $aktivitas_kuliah, 'transkrip' => $transkrip_mahasiswa, 'total_sks_transkrip'=>$total_sks_transkrip, 
                    'bobot'=>$bobot,'ipk'=>$ipk, 'statusSync' => $statusSync, 'id_batch' => $id_batch]);
    }

    public function data_akademik_store(Request $request)
    {
        // dd($request->all());
        // Validate request data
        $request->validate([
            'lokasi_kuliah' => 'required',
        ]);

        // Define variable
        $id_reg = auth()->user()->fk_id;

        $semester_aktif = SemesterAktif::first();

        $riwayat_pendidikan = RiwayatPendidikan::with('biodata', 'biodata.wilayah.kab_kota')
                    ->where('id_registrasi_mahasiswa', $id_reg)
                    ->first();

        $akm = AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa', $id_reg)
                ->whereRaw('RIGHT(id_semester, 1) != ?', [3])
                ->orderBy('id_semester', 'desc')
                ->first();
        
        $ipk = TranskripMahasiswa::where('id_registrasi_mahasiswa', $id_reg)
                ->whereNot('nilai_huruf', 'F')
                ->get();

        $ipk_total = 0;
        $sks_total = 0;

        foreach ($ipk as $nilai) {
            $ipk_total += $nilai->nilai_indeks * $nilai->sks_mata_kuliah;
            $sks_total += $nilai->sks_mata_kuliah;
        }

        $ipk = $sks_total ? round($ipk_total / $sks_total, 2) : 0;

        // dd($sks_total, $ipk_total, $ipk);
        if($request->wisuda_ke == '0') {
            return redirect()->back()->with('error',
                'Tidak ada periode Wisuda yang tersedia !!');
        }

        $sks_total_akm = AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa', $id_reg)
                ->orderBy('id_semester', 'desc')
                ->value('sks_total') ?? 0;
                
        if($sks_total_akm != $sks_total) {
            return redirect()->back()->with('error', 'SKS Total pada AKM dan Transkrip tidak sesuai, silahkan hubungi Koor. Prodi!');
        }

        try {
            DB::beginTransaction();

            $wisuda = Wisuda::where('id_registrasi_mahasiswa', $id_reg)
            ->update([
                'sks_diakui' => $akm->sks_total,
                'ipk' => $ipk,
                'angkatan' => $akm->angkatan,
                'lokasi_kuliah' => $request->lokasi_kuliah,
                'verified_akademik' => 1,
            ]);

            DB::commit();

            // dd($request->all());

            // Redirect kembali ke halaman index dengan pesan sukses
            return redirect()->route('mahasiswa.wisuda.pendaftaran.data-tugas-akhir')->with('success', 'Data Berhasil disimpan, Silahkan lanjut ke Data Tugas Akhir!');
        } 
        catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Wisuda store error: '.$e->getMessage());
            // Handle exception
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data!');
        }
    }


    //DATA TUGAS AKHIR
    public function data_tugas_akhir()
    {
        $id_reg = auth()->user()->fk_id;

        $riwayat_pendidikan = RiwayatPendidikan::with('biodata','lulus_do','prodi', 'prodi.fakultas', 'prodi.jurusan', 'biodata.wilayah.kab_kota')->where('id_registrasi_mahasiswa', $id_reg)->first();

        $semester_aktif=SemesterAktif::with('semester')->first();

        $bku_prodi = BkuProgramStudi::where('id_prodi', $riwayat_pendidikan->id_prodi)->get();

        $today = Carbon::now()->toDateString();

        $aktivitas = AktivitasMahasiswa::with(['anggota_aktivitas_personal', 'bimbing_mahasiswa', 'nilai_konversi'])
                ->whereHas('bimbing_mahasiswa', function ($query) {
                        $query->whereNotNull('id_bimbing_mahasiswa');
                    })
                ->whereHas('anggota_aktivitas_personal', function ($query) use ($riwayat_pendidikan) {
                        $query->where('id_registrasi_mahasiswa', $riwayat_pendidikan->id_registrasi_mahasiswa)
                        // ->where('nim', $riwayat_pendidikan->nim)
                        ;
                    })
                ->whereHas('nilai_konversi', function ($query) {
                        $query->where('nilai_indeks', '>', 0.00);
                    })
                ->where('id_prodi', $riwayat_pendidikan->id_prodi)
                ->whereIn('id_jenis_aktivitas', ['1', '3', '4', '22'])
                ->orderByDesc('id_semester') // aktivitas terakhir
                ->first();

        // dd($aktivitas);
        $wisuda = Wisuda::where('id_registrasi_mahasiswa', $id_reg)->first();

        return view('mahasiswa.wisuda.pendaftaran.data-tugas-akhir', [
                    'riwayat_pendidikan' => $riwayat_pendidikan, 'semester_aktif' => $semester_aktif, 
                    'wisuda' => $wisuda, 'aktivitas' => $aktivitas, 'bku_prodi'=> $bku_prodi]);
    }

    public function data_tugas_akhir_store(Request $request)
    {
        $request->validate([
            'abstrak_ta' => 'required',
            'bku_prodi' => 'nullable',
            'abstrak_file' => 'nullable|file|mimes:pdf|max:500',
        ], [
            'abstrak_file.max' => 'Ukuran file abstrak maksimal 500 KB.',
        ]);

        // VALIDASI JUMLAH KATA
        $wordCount = str_word_count(strip_tags($request->abstrak_ta));

        if ($wordCount > 500) {
            return back()
                ->withErrors([
                    'abstrak_ta' => "Abstrak maksimal 500 kata. Saat ini: $wordCount kata."
                ])
                ->withInput();
        }

        $id_reg = auth()->user()->fk_id;

        $riwayat_pendidikan = RiwayatPendidikan::with('biodata', 'biodata.wilayah.kab_kota')
            ->where('id_registrasi_mahasiswa', $id_reg)
            ->first();

        $aktivitas = AktivitasMahasiswa::with(['anggota_aktivitas_personal','bimbing_mahasiswa','nilai_konversi'])
            ->whereHas('bimbing_mahasiswa', function ($query) {
                $query->whereNotNull('id_bimbing_mahasiswa');
            })
            ->whereHas('anggota_aktivitas_personal', function ($query) use ($riwayat_pendidikan) {
                $query->where('id_registrasi_mahasiswa', $riwayat_pendidikan->id_registrasi_mahasiswa)
                    // ->where('nim', $riwayat_pendidikan->nim)
                    ;
            })
            ->whereHas('nilai_konversi', function ($query) {
                $query->where('nilai_indeks', '>', 2.00);
            })
            ->where('id_prodi', $riwayat_pendidikan->id_prodi)
            ->whereIn('id_jenis_aktivitas', ['1','3','4','22'])
            ->orderBy('id_semester','desc')
            ->first();

        DB::beginTransaction();

        try {

            $wisuda = Wisuda::where('id_registrasi_mahasiswa', $id_reg)->first();

            /*
            |--------------------------------------------------------------------------
            | HANDLE FILE ABSTRAK
            |--------------------------------------------------------------------------
            */

            $abstrak_file = $wisuda->abstrak_file ?? null;

            if ($request->hasFile('abstrak_file')) {

                $file = $request->file('abstrak_file');

                $abstrakName = 'abstrak_' . str_replace(' ', '_', $riwayat_pendidikan->nim) . '.' . $file->getClientOriginalExtension();

                $abstrakDir = storage_path('app/public/wisuda/abstrak');

                if (!file_exists($abstrakDir)) {
                    mkdir($abstrakDir, 0775, true);
                }

                $abstrakPath = $file->storeAs('wisuda/abstrak', $abstrakName, 'public');

                if (!$abstrakPath) {
                    return redirect()->back()->with('error', 'File abstrak gagal diunggah.');
                }

                $abstrak_file = 'storage/' . $abstrakPath;
            }

            /*
            |--------------------------------------------------------------------------
            | UPDATE DATA WISUDA
            |--------------------------------------------------------------------------
            */

            Wisuda::where('id_registrasi_mahasiswa', $id_reg)
                ->update([
                    'id_aktivitas' => $aktivitas->id_aktivitas,
                    'tgl_sk_pembimbing' => $aktivitas->tanggal_sk_tugas,
                    'no_sk_pembimbing' => $aktivitas->sk_tugas,
                    'judul_eng' => strtoupper($request->judul_eng),
                    'abstrak_ta' => strtoupper($request->abstrak_ta),
                    'abstrak_file' => $abstrak_file,
                    'id_bku_prodi' => $request->bku_prodi,
                    'verified_ta' => 1,
                ]);

            DB::commit();

            return redirect()
                ->route('mahasiswa.wisuda.pendaftaran.data-wisuda')
                ->with('success','Data Berhasil disimpan, Silahkan lanjut ke Data Wisuda!');

        } 
        catch (\Exception $e) {

            DB::rollBack();

            \Log::error('Wisuda store error: '.$e->getMessage());

            return redirect()->back()->with('error','Terjadi kesalahan saat menyimpan data!');
        }

        //         catch (\Exception $e) {
        //     DB::rollBack();
        //     dd($e->getMessage(), $e->getLine(), $e->getFile());
        // }
    }


    //DATA WISUDA
    public function data_wisuda()
    {
        $id_reg = auth()->user()->fk_id;

        $riwayat_pendidikan = RiwayatPendidikan::with('biodata','lulus_do','prodi', 'prodi.fakultas', 'prodi.jurusan', 'biodata.wilayah.kab_kota')->where('id_registrasi_mahasiswa', $id_reg)->first();

        $semester_aktif=SemesterAktif::with('semester')->first();

        $today = Carbon::now()->toDateString();

        $wisuda_ke = PeriodeWisuda::where('tanggal_mulai_daftar', '<=', $today)
                    ->where('tanggal_akhir_daftar', '>=', $today)
                    ->where('is_active', '1')
                    ->first();

                    // dd($kecamatan->where('id_wilayah',999999));

        $wisuda = Wisuda::where('id_registrasi_mahasiswa', $id_reg)->first();

        return view('mahasiswa.wisuda.pendaftaran.data-wisuda', ['riwayat_pendidikan' => $riwayat_pendidikan, 'semester_aktif' => $semester_aktif, 
                    'wisuda_ke' => $wisuda_ke, 'wisuda' => $wisuda]);
    }

    public function data_wisuda_store(Request $request)
    {
        $request->validate([
            'wisuda_ke' => 'required',
            'pas_foto' => 'nullable|file|mimes:jpeg,jpg,png|max:500',
        ], [
            'pas_foto.max' => 'Ukuran pas foto maksimal 500 KB.',
        ]);

        $id_reg = auth()->user()->fk_id;

        $riwayat_pendidikan = RiwayatPendidikan::with('biodata', 'biodata.wilayah.kab_kota')
            ->where('id_registrasi_mahasiswa', $id_reg)
            ->first();

        if ($request->wisuda_ke == '0') {
            return redirect()->back()->with('error','Tidak ada periode Wisuda yang tersedia !!');
        }

        DB::beginTransaction();

        try {

            $wisuda = Wisuda::where('id_registrasi_mahasiswa', $id_reg)->first();

            /*
            |--------------------------------------------------------------------------
            | HANDLE PAS FOTO
            |--------------------------------------------------------------------------
            */

            $pas_foto = $wisuda->pas_foto ?? null;

            if ($request->hasFile('pas_foto')) {

                $file = $request->file('pas_foto');

                $pasFotoName = 'pas_foto_' . str_replace(' ', '_', $riwayat_pendidikan->nim) . '.' . $file->getClientOriginalExtension();

                $pasFotoDir = storage_path('app/public/wisuda/pas_foto');

                if (!file_exists($pasFotoDir)) {
                    mkdir($pasFotoDir, 0775, true);
                }

                $pasFotoPath = $file->storeAs('wisuda/pas_foto', $pasFotoName, 'public');

                if (!$pasFotoPath) {
                    return redirect()->back()->with('error','Pas foto gagal diunggah. Silakan coba lagi.');
                }

                $pas_foto = $pasFotoPath;
            }

            /*
            |--------------------------------------------------------------------------
            | UPDATE DATA
            |--------------------------------------------------------------------------
            */

            Wisuda::where('id_registrasi_mahasiswa', $id_reg)
                ->update([
                    'wisuda_ke' => $request->wisuda_ke,
                    'pas_foto' => $pas_foto,
                    'verified_wisuda' => 1,
                ]);

            DB::commit();

            return redirect()
                ->route('mahasiswa.wisuda.pendaftaran.data-skpi')
                ->with('success','Data Berhasil disimpan, Silahkan lanjut ke Data SKPI!');

        } catch (\Exception $e) {

            DB::rollBack();

            \Log::error('Wisuda store error: '.$e->getMessage());

            return redirect()->back()->with('error','Terjadi kesalahan saat menyimpan data!');
        }
    }

    //DATA SKPI
    public function data_skpi()
    {
        // dd($semester_aktif->id_semester);
        $id_reg = auth()->user()->fk_id;

        $riwayat_pendidikan = RiwayatPendidikan::with('biodata','lulus_do','prodi', 'prodi.fakultas', 'prodi.jurusan', 'biodata.wilayah.kab_kota')->where('id_registrasi_mahasiswa', $id_reg)->first();

        $semester_aktif=SemesterAktif::with('semester')->first();

        $today = Carbon::now()->toDateString();

        $wisuda = Wisuda::where('id_registrasi_mahasiswa', $id_reg)->first();

        $skpi_bidang = SKPIBidangKegiatan::all();

        $skpi_data = SKPI::leftJoin('skpi_jenis_kegiatan', 'skpi_jenis_kegiatan.id', 'skpi_data.id_jenis_skpi')
                    ->select('skpi_data.*', 'skpi_jenis_kegiatan.bidang_id', 'skpi_jenis_kegiatan.kriteria')
                    ->where('skpi_data.id_registrasi_mahasiswa', $id_reg)
                    ->get();

        // dd($skpi_data);
        $skpi_jenis_kegiatan = SKPIJenisKegiatan::all();
                    // dd($skpi_jenis_kegiatan);
        return view('mahasiswa.wisuda.pendaftaran.data-skpi', ['riwayat_pendidikan' => $riwayat_pendidikan, 'semester_aktif' => $semester_aktif, 
                    'wisuda' => $wisuda, 'skpi_bidang' => $skpi_bidang, 'skpi_data' => $skpi_data, 'skpi_jenis_kegiatan' => $skpi_jenis_kegiatan]);
    }

    public function skpi_store(Request $request)
    {
        try {

            $id_reg = auth()->user()->fk_id;

            $wisuda = Wisuda::where('id_registrasi_mahasiswa', $id_reg)->first();

            if(!$wisuda){
                return back()->with('error','Data wisuda tidak ditemukan');
            }

            $wisuda->update([
                'verified_skpi' => 1
            ]);

            return redirect()
                ->route('mahasiswa.wisuda.pendaftaran.resume.index')
                ->with('success', 'Data SKPI Berhasil difinalisasi');

        } catch (\Exception $e) {

            return back()->with('error','Gagal finalisasi data SKPI');

        }
    }

    public function data_skpi_store(Request $request)
    {

        $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'tahun_kegiatan' => 'required|digits:4',
            'id_jenis_skpi' => 'required|exists:skpi_jenis_kegiatan,id',
            'file_pendukung' => 'required|mimes:pdf|max:500'
        ]);

        try {

            $id_reg = auth()->user()->fk_id;

            $riwayat_pendidikan = RiwayatPendidikan::where('id_registrasi_mahasiswa', $id_reg)->first();

            $semester_aktif=SemesterAktif::with('semester')->first();

            $jenis = SKPIJenisKegiatan::findOrFail($request->id_jenis_skpi);

            $wisuda = Wisuda::where('id_registrasi_mahasiswa', $id_reg)->first();

            if ($wisuda->finalisasi_data == 1) {
                return redirect()->back()->with('error', 'Data telah difinalisasi, perubahan data tidak diperbolehkan');
            }
            
            $filePath = null;

            if ($request->hasFile('file_pendukung')) {

                $file = $request->file('file_pendukung');

                $fileName = $riwayat_pendidikan->nim.'_'.time().'.'.$file->getClientOriginalExtension();

                $filePath = $file->storeAs('skpi', $fileName, 'public');

            }

            SKPI::create([
                'id_registrasi_mahasiswa' => $id_reg,
                'id_prodi' => $riwayat_pendidikan->id_prodi,
                'id_semester' => $semester_aktif->id_semester,
                'nama_kegiatan' => $request->nama_kegiatan,
                'tahun' => $request->tahun_kegiatan,
                'id_jenis_skpi' => $jenis->id,
                'nama_jenis_skpi' => $jenis->nama_jenis,
                'periode_wisuda' => $wisuda ? $wisuda->wisuda_ke : null,
                'file_pendukung' => $filePath,
                'skor' => $jenis->skor
            ]);

            return redirect()
                ->route('mahasiswa.wisuda.pendaftaran.data-skpi')
                ->with('success', 'Data SKPI Berhasil ditambahkan');

        } catch (\Exception $e) {

            return redirect()
                ->back()
                ->withInput()
                
                ->with('error', 'Terjadi kesalahan saat menyimpan data SKPI.')
                // jika ingin debug bisa pakai:
                ->with('error', $e->getMessage());
        }
    }

    public function data_skpi_update(Request $request,$id)
    {
        $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'tahun_kegiatan' => 'required|digits:4',
            'id_jenis_skpi' => 'required|exists:skpi_jenis_kegiatan,id',
            'file_pendukung' => 'nullable|mimes:pdf|max:500'
        ]);

        $riwayat_pendidikan = RiwayatPendidikan::where('id_registrasi_mahasiswa', auth()->user()->fk_id)->first();

        $data = SKPI::findOrFail($id);

        $wisuda = Wisuda::where('id_registrasi_mahasiswa', $data->id_registrasi_mahasiswa)->first();

        if ($wisuda->finalisasi_data == 1) {
            return redirect()->back()->with('error', 'Data telah difinalisasi, perubahan data tidak diperbolehkan');
        }

        $jenis = SKPIJenisKegiatan::findOrFail($request->id_jenis_skpi);

        try {

            $filePath = $data->file_pendukung;

            if($request->hasFile('file_pendukung')){

                if($data->file_pendukung && Storage::disk('public')->exists($data->file_pendukung)){
                    Storage::disk('public')->delete($data->file_pendukung);
                }

                $file = $request->file('file_pendukung');

                $filename = $riwayat_pendidikan->nim.'_'.time().'.pdf';

                $filePath = $file->storeAs('skpi',$filename,'public');
            }

            $data->update([
                'nama_kegiatan' => $request->nama_kegiatan,
                'tahun' => $request->tahun_kegiatan,
                'id_jenis_skpi' => $jenis->id,
                'nama_jenis_skpi' => $jenis->nama_jenis,
                'skor' => $jenis->skor,
                'file_pendukung' => $filePath
            ]);

            return back()->with('success','Data SKPI berhasil diperbarui');
        } 
        // catch (\Exception $e) {

        //     return back()->with('error','Gagal update data');

        // }

        catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage(), $e->getLine(), $e->getFile());
        }
    }

    public function data_skpi_delete($id)
    {
        try {

            $data = SKPI::findOrFail($id);

            //CEK STATUS VER SKPI
            $wisuda = Wisuda::where('id_registrasi_mahasiswa', $data->id_registrasi_mahasiswa)->first();
            
            if ($wisuda->finalisasi_data == 1) {
                return redirect()->back()->with('error', 'Data telah difinalisasi, perubahan data tidak diperbolehkan');
            }

            // pastikan hanya mahasiswa pemilik data yang bisa hapus
            if ($data->id_registrasi_mahasiswa != auth()->user()->fk_id) {
                return redirect()->back()->with('error', 'Tidak memiliki akses untuk menghapus data ini');
            }

            // HAPUS FILE JIKA ADA
            if ($data->file_pendukung && Storage::disk('public')->exists($data->file_pendukung)) {
                Storage::disk('public')->delete($data->file_pendukung);
            }

            // HAPUS DATA
            $data->delete();

            return redirect()->back()->with('success', 'Data SKPI berhasil dihapus');

        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'Gagal menghapus data SKPI');
        }
    }

    //DATA INDUK
    public function resume_yudisium()
    {
        // dd($semester_aktif->id_semester);
        $id_reg = auth()->user()->fk_id;

        $riwayat_pendidikan = RiwayatPendidikan::with('biodata','lulus_do','prodi', 'prodi.fakultas', 'prodi.jurusan', 'biodata.wilayah.kab_kota')->where('id_registrasi_mahasiswa', $id_reg)->first();

        // dd($riwayat_pendidikan -> biodata->wilayah->nama_wilayah);

        $semester_aktif=SemesterAktif::with('semester')->first();

        $kecamatan = Wilayah::with('level', 'kab_kota')->where('id_level_wilayah', 3)->get();

        $today = Carbon::now()->toDateString();

        $wisuda = Wisuda::where('id_registrasi_mahasiswa', $id_reg)->first();

        $wisuda_ke = PeriodeWisuda::where('tanggal_mulai_daftar', '<=', $today)
                    ->where('tanggal_akhir_daftar', '>=', $today)
                    ->where('is_active', '1')
                    ->first();
        
        if(!$wisuda_ke) {
            return redirect()->back()->with('error',
                'Tidak ada periode Wisuda yang tersedia !!');
        }

        // Cek apakah file berhasil diupload
        // if ($wisuda) {
        //     return redirect()->back()->with('error', 'Anda telah melakukan pendaftaran wisuda !!');
        // }

        if(!$riwayat_pendidikan->id_kurikulum ) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kurikulum Mahasiswa Belum Diatur!!',
            ]);
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

        if($useptData['class'] == 'danger') {
            return redirect()->back()->with('error', 'Nilai USEPT Anda belum memenuhi syarat, Silahkan lakukan ujian ulang!');
        }

        $bebas_pustaka = BebasPustaka::where('id_registrasi_mahasiswa', $id_reg)->first();

        // dd($riwayat_pendidikan->lulus_do, $wisuda, $riwayat_pendidikan->id_jenis_keluar);
        
        if ($riwayat_pendidikan->lulus_do) {
            return redirect()->back()->with('error','Anda tidak diizinkan mengakses halaman wisuda, status mahasiswa Anda adalah '. $riwayat_pendidikan->lulus_do->nama_jenis_keluar . '!');
        }elseif (!empty($riwayat_pendidikan->id_jenis_keluar)) {
            return redirect()->back()->with('error','Anda tidak diizinkan mengakses halaman wisuda, status mahasiswa Anda adalah '. $riwayat_pendidikan->keterangan_keluar . '!');
        }

        if (!$bebas_pustaka) {
            return redirect()->back()->with('error', 'Anda belum melakukan upload repository dan bebas pustaka, silakan menghubungi Admin Perpustakaan.');
        }

        if (empty($bebas_pustaka->file_bebas_pustaka)) {
            return redirect()->back()->with('error', 'File bebas pustaka belum diupload, silakan menghubungi Admin Perpustakaan.');
        }

        if (empty($bebas_pustaka->link_repo)) {
            return redirect()->back()->with('error', 'Link repository belum diisi, silakan menghubungi Admin Perpustakaan.');
        }

        try {
            $asal_sekolah = Registrasi::leftJoin('data_sekolah', 'data_sekolah.npsn', 'reg_master.rm_pddk_slta_kode')
                    ->select('reg_master.rm_pddk_sd_nama','reg_master.rm_pddk_sd_lokasi', 'reg_master.rm_pddk_sd_thn_lulus',
                            'reg_master.rm_pddk_sltp_nama', 'reg_master.rm_pddk_sltp_lokasi', 'reg_master.rm_pddk_sltp_thn_lulus',
                            'data_sekolah.nama_sekolah', 'reg_master.rm_pddk_slta_jurusan', 'reg_master.rm_pddk_slta_thn_lulus',
                            'data_sekolah.nama_kabupaten', 'data_sekolah.nama_provinsi', 'data_sekolah.akreditasi_sekolah')
                    ->where('rm_nim', $riwayat_pendidikan->nim)
                    ->first();
        } catch (\Exception $e) {

            // log error
            \Log::error('Koneksi database gagal: '.$e->getMessage());

            // nilai default jika gagal
            $asal_sekolah = null;
        }


        //DATA AKADEMIK START
        $aktivitas_kuliah = AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa', $id_reg)
                // ->where('id_semester', $semester_aktif->id_semester)
                ->orderBy('id_semester', 'ASC')
                ->get();
        
        //DATA TRANSKRIP MAHASISWA
        $jobData =  DB::table('job_batches')->where('name', 'transkrip-mahasiswa')->where('pending_jobs', '>', 0)->first();

        $statusSync = $jobData ? 1 : 0;

        $id_batch = $jobData ? $jobData->id : null;
        
        $transkrip_mahasiswa=TranskripMahasiswa::where('id_registrasi_mahasiswa',$id_reg)->orderBy('nama_mata_kuliah','asc')->get();

        $total_sks_transkrip = $transkrip_mahasiswa ->whereNotNull('nilai_indeks')->sum('sks_mata_kuliah');

        $bobot = 0;
        
        foreach ($transkrip_mahasiswa as $t) {
            $bobot += $t->nilai_indeks * $t->sks_mata_kuliah;
        }

        
        if($total_sks_transkrip != 0){
            $ipk = number_format($bobot / $total_sks_transkrip, 2);
        }else{
            $ipk=0;
        }
        //DATA AKADEMIK END


        //DATA TUGAS AKHIR START
        $bku_prodi = BkuProgramStudi::where('id_prodi', $riwayat_pendidikan->id_prodi)->get();

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
                ->where('id_prodi', $riwayat_pendidikan->id_prodi)
                ->whereIn('id_jenis_aktivitas', ['1', '3', '4', '22'])
                ->orderByDesc('id_semester') // aktivitas terakhir
                ->first();
        //DATA TUGAS AKHIR END


        //DATA WISUDA START
        $wisuda_ke = PeriodeWisuda::where('tanggal_mulai_daftar', '<=', $today)
                    ->where('tanggal_akhir_daftar', '>=', $today)
                    ->where('is_active', '1')
                    ->first();
        //DATA WISUDA END


        //DATA SKPI START
        $skpi_bidang = SKPIBidangKegiatan::all();

        $skpi_data = SKPI::leftJoin('skpi_jenis_kegiatan', 'skpi_jenis_kegiatan.id', 'skpi_data.id_jenis_skpi')
                    ->select('skpi_data.*', 'skpi_jenis_kegiatan.bidang_id', 'skpi_jenis_kegiatan.kriteria')
                    ->where('skpi_data.id_registrasi_mahasiswa', $id_reg)
                    ->get();

        $skpi_jenis_kegiatan = SKPIJenisKegiatan::all();
        //DATA SKPI END


        return view('mahasiswa.wisuda.pendaftaran.resume_yudisium', ['riwayat_pendidikan' => $riwayat_pendidikan, 'semester_aktif' => $semester_aktif, 
                    'kecamatan'=> $kecamatan, 'usept' => $useptData, 'bebas_pustaka' => $bebas_pustaka, 'asal_sekolah' => $asal_sekolah, 'wisuda' => $wisuda,
                    'aktivitas_kuliah' => $aktivitas_kuliah, 'transkrip' => $transkrip_mahasiswa, 'total_sks_transkrip'=>$total_sks_transkrip, 
                    'bobot'=>$bobot,'ipk'=>$ipk, 'statusSync' => $statusSync, 'id_batch' => $id_batch, 'aktivitas' => $aktivitas, 'bku_prodi'=> $bku_prodi, 
                    'wisuda_ke' => $wisuda_ke, 'skpi_bidang' => $skpi_bidang, 'skpi_data' => $skpi_data, 'skpi_jenis_kegiatan' => $skpi_jenis_kegiatan
                ]);
    }

    public function finalisasi_data(Request $request)
    {
        $id_reg = auth()->user()->fk_id;

        $wisuda = Wisuda::where('id_registrasi_mahasiswa', $id_reg)->first();

        if(!$wisuda){
            return back()->with('error','Data wisuda tidak ditemukan');
        }

        if($wisuda->verified_induk == 0){
            return redirect()->route('mahasiswa.wisuda.pendaftaran.data-induk')->with('error', 'Silahkan Pastikan Data Induk telah disimpan!');
        }

        if($wisuda->verified_akademik == 0){
            return redirect()->route('mahasiswa.wisuda.pendaftaran.data-akademik')->with('error', 'Silahkan Pastikan Data Akademik telah disimpan!');
        }

        if($wisuda->verified_ta == 0){
            return redirect()->route('mahasiswa.wisuda.pendaftaran.data-tugas-akhir')->with('error', 'Silahkan Pastikan Tugas Akhir telah disimpan!');
        }

        if($wisuda->verified_wisuda == 0){
            return redirect()->route('mahasiswa.wisuda.pendaftaran.data-wisuda')->with('error', 'Silahkan Pastikan Data Wisuda telah disimpan!');
        }

        if($wisuda->verified_skpi == 0 ){
            return redirect()->route('mahasiswa.wisuda.pendaftaran.data-skpi')->with('error', 'Silahkan Pastikan Data SKPI telah disimpan!');
        }

        try {

            $wisuda->update([
                'finalisasi_data' => 1
            ]);

            return redirect()
                ->route('mahasiswa.wisuda.index')
                ->with('success', 'Data Pendaftaran Berhasil difinalisasi');

        } catch (\Exception $e) {

            return back()->with('error','Gagal finalisasi data SKPI');

        }
    }

    public function peserta_formulir(Wisuda $id)
    {
        // dd($id);
        if(!$id -> tgl_sk_yudisium){
            return redirect()->back()->with('error', 'SK Yudisium belum diisi Fakultas!');
        }
        
        $riwayat = RiwayatPendidikan::with(['prodi.fakultas', 'biodata'])->where('id_registrasi_mahasiswa', $id->id_registrasi_mahasiswa)->first();
        $biodata = BiodataMahasiswa::where('id_mahasiswa', $riwayat->id_mahasiswa)->first();
        $aktivitas = AktivitasMahasiswa::with('bimbing_mahasiswa.dosen')->where('id_aktivitas', $id->id_aktivitas)->first();
        $pt = AllPt::where('id_perguruan_tinggi', $id->id_perguruan_tinggi)->select('nama_perguruan_tinggi')->first();
        $syaratAdm = WisudaSyaratAdm::orderBy('urutan')->select('syarat')->get();
        $checklist = WisudaChecklist::orderBy('urutan')->select('checklist')->get();

        Carbon::setLocale('id');
        $now = Carbon::now()->format('d-m-Y');
        $now = Carbon::createFromFormat('d-m-Y', $now)->translatedFormat('d F Y');

        $pdf = PDF::loadview('bak.wisuda.peserta.formulir', [
            'riwayat' => $riwayat,
            'biodata' => $biodata,
            'aktivitas' => $aktivitas,
            'pt' => $pt,
            'data' => $id,
            'syaratAdm' => $syaratAdm,
            'checklist' => $checklist,
            'now' => $now,
         ])
         ->setPaper('legal', 'portrait');

        //  dd($riwayat, $biodata, $pt, $id, $syaratAdm, $checklist, $now);

         return $pdf->stream('Formulir_pendaftara_wisuda-'.$riwayat->nim.'.pdf');
    }

    public function transkrip_mahasiswa()
    {
        $id_reg_mhs = auth()->user()->fk_id;

        $jobData =  DB::table('job_batches')->where('name', 'transkrip-mahasiswa')->where('pending_jobs', '>', 0)->first();

        $statusSync = $jobData ? 1 : 0;

        $id_batch = $jobData ? $jobData->id : null;

        // dd($jobData);
        $transkrip_mahasiswa=TranskripMahasiswa::where('id_registrasi_mahasiswa',$id_reg_mhs)->orderBy('nama_mata_kuliah','asc')->get();

        $total_sks_transkrip = $transkrip_mahasiswa ->whereNotNull('nilai_indeks')->sum('sks_mata_kuliah');

        $bobot = 0;
        
        foreach ($transkrip_mahasiswa as $t) {
            $bobot += $t->nilai_indeks * $t->sks_mata_kuliah;
        }

        
        if($total_sks_transkrip != 0){
            $ipk = number_format($bobot / $total_sks_transkrip, 2);
        }else{
            $ipk=0;
        }
        
        return view('mahasiswa.wisuda.transkrip-mahasiswa', ['transkrip' => $transkrip_mahasiswa, 'total_sks_transkrip'=>$total_sks_transkrip, 'bobot'=>$bobot,'ipk'=>$ipk, 'statusSync' => $statusSync, 'id_batch' => $id_batch,]);
    }
    
}
