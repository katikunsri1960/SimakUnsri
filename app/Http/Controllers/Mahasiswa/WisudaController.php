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
use App\Models\BkuProgramStudi;

class WisudaController extends Controller
{
    public function index(Request $request)
    {
        $id_reg = auth()->user()->fk_id;

        $semester_aktif = SemesterAktif::first();

        $riwayat_pendidikan = RiwayatPendidikan::with('prodi', 'prodi.fakultas', 'prodi.jurusan', 'lulus_do')
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

        if ($aktivitas_kuliah->sks_total < $kurikulum->jumlah_sks_lulus) {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Anda tidak dapat melakukan pendaftaran wisuda, Silahkan selesaikan minimal '.$kurikulum->jumlah_sks_lulus.' sks!');
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

        if (!$aktivitas) {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Anda tidak dapat melakukan pendaftaran wisuda, Silahkan selesaikan Aktivitas Tugas Akhir!');
        }

        $wisuda = Wisuda::with('bku_prodi')
                ->where('id_registrasi_mahasiswa', $id_reg)
                    ->whereHas('periode_wisuda', function($q){
                        $q->where('is_active', 1);
                    })
                    ->with([
                        'aktivitas_mahasiswa',
                        'periode_wisuda' => function ($q) {
                            $q->where('is_active', 1);
                        }
                    ])
                    ->first();

        if (!$riwayat_pendidikan->lulus_do && $riwayat_pendidikan->id_jenis_keluar != 1) {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Anda tidak diizinkan mengakses halaman wisuda, Anda masih berstatus Mahasiswa Aktif!');
        }

        if ($riwayat_pendidikan->lulus_do && $riwayat_pendidikan->lulus_do->id_jenis_keluar != 1 && $wisuda == null) {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Anda tidak diizinkan mengakses halaman wisuda, status mahasiswa Anda adalah '.$riwayat_pendidikan->lulus_do->nama_jenis_keluar.'!');
        }

        $bebas_pustaka = BebasPustaka::where('id_registrasi_mahasiswa', $id_reg)->first();

        if (!$bebas_pustaka) {
            return redirect()
                ->route('mahasiswa.dashboard')
                ->with('error', 'Anda belum melakukan upload repository dan bebas pustaka, silakan menghubungi Admin Perpustakaan.');
        }

        if(!$wisuda){
            if (empty($bebas_pustaka->file_bebas_pustaka)) {
                return redirect()
                    ->route('mahasiswa.dashboard')
                    ->with('error', 'File bebas pustaka belum diupload, silakan menghubungi Admin Perpustakaan.');
            }

            if (empty($bebas_pustaka->link_repo)) {
                return redirect()
                    ->route('mahasiswa.dashboard')
                    ->with('error', 'Link repository belum diisi, silakan menghubungi Admin Perpustakaan.');
            }
        }
        

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
        ]);
    }

    public function tambah()
    {
        // dd($semester_aktif->id_semester);
        $id_reg = auth()->user()->fk_id;

        $riwayat_pendidikan = RiwayatPendidikan::with('biodata', 'prodi', 'prodi.fakultas', 'prodi.jurusan', 'biodata.wilayah.kab_kota')->where('id_registrasi_mahasiswa', $id_reg)->first();

        $semester_aktif=SemesterAktif::with('semester')->first();

        $kecamatan = Wilayah::with('level', 'kab_kota')->where('id_level_wilayah', 3)->get();

        $bku_prodi = BkuProgramStudi::where('id_prodi', $riwayat_pendidikan->id_prodi)->get();

        $today = Carbon::now()->toDateString();

        $wisuda_ke = PeriodeWisuda::where('tanggal_mulai_daftar', '<=', $today)
                    ->where('tanggal_akhir_daftar', '>=', $today)
                    ->where('is_active', '1')
                    ->first();

                    // dd($kecamatan->where('id_wilayah',999999));

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

        // dd($riwayat_pendidikan);

        $wisuda = Wisuda::where('id_registrasi_mahasiswa', $id_reg)->first();

        // Cek apakah file berhasil diupload
        if ($wisuda) {
            return redirect()->back()->with('error', 'Anda telah melakukan pendaftaran wisuda !!');
        }

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

        if (!$bebas_pustaka) {
            return redirect()->back()->with('error', 'Anda belum melakukan upload repository atau bebas pustaka, silahkan menghubungi Admin Perpustakaan !!');
        }
        // dd($useptData);

        $asal_sekolah = Registrasi::leftJoin('data_sekolah', 'data_sekolah.npsn', 'reg_master.rm_pddk_slta_kode')
                    ->select('reg_master.rm_pddk_sd_nama','reg_master.rm_pddk_sd_lokasi', 'reg_master.rm_pddk_sd_thn_lulus',
                            'reg_master.rm_pddk_sltp_nama', 'reg_master.rm_pddk_sltp_lokasi', 'reg_master.rm_pddk_sltp_thn_lulus',
                            'data_sekolah.nama_sekolah', 'reg_master.rm_pddk_slta_jurusan', 'reg_master.rm_pddk_slta_thn_lulus',
                            'data_sekolah.nama_kabupaten', 'data_sekolah.nama_provinsi', 'data_sekolah.akreditasi_sekolah')
                    ->where('rm_nim', $riwayat_pendidikan->nim)->first();
        // dd($bku_prodi);

        return view('mahasiswa.wisuda.store', ['riwayat_pendidikan' => $riwayat_pendidikan, 'semester_aktif' => $semester_aktif, 'kecamatan'=> $kecamatan,'wisuda_ke' => $wisuda_ke,
                    'aktivitas' => $aktivitas, 'usept' => $useptData, 'bebas_pustaka' => $bebas_pustaka, 'asal_sekolah' => $asal_sekolah, 'bku_prodi'=> $bku_prodi]);
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

    // public function get_bku(Request $request)
    // {
    //     $db = new BkuProgramStudi();

    //     $data = $db->where('bku_prodi_id', 'like', '%'.$request->q.'%')
    //                 ->where('bku_prodi_id', 'like', '%'.$request->q.'%')
    //                 ->where('id_level_wilayah', 3)
    //                 ->orderBy('id', 'desc')->get();

    //     return response()->json($data);
    // }

    public function store(Request $request)
    {
        // dd($request->all());
        // Validate request data
        $request->validate([
            'no_hp_ayah' => 'required|regex:/^[0-9]+$/',
            'no_hp_ibu' => 'required|regex:/^[0-9]+$/',
            'nama_ayah' => 'required|regex:/^[a-zA-Z\s]+$/',
            'nik' => 'required',
            'id_wilayah' => 'required',
            'lokasi_kuliah' => 'required',
            'wisuda_ke' => 'required',

            // abstrak_ta → TANPA max
            'abstrak_ta' => 'required',

            'pas_foto' => 'required|file|mimes:jpeg,jpg,png|max:500',
            'bku_prodi' => 'nullable',
            'abstrak_file' => 'required|file|mimes:pdf|max:1024',
            'ijazah_terakhir_file' => 'required|file|mimes:pdf|max:1024',
            'alamat_orang_tua' => 'required',
        ], [
            'pas_foto.max' => 'Ukuran pas foto maksimal 500 KB.',
            'abstrak_file.max' => 'Ukuran file abstrak maksimal 1 MB.',
            'ijazah_terakhir_file.max' => 'Ukuran file ijazah terakhir maksimal 1 MB.',
        ]);

        // ✅ VALIDASI KHUSUS JUMLAH KATA
        $wordCount = str_word_count(strip_tags($request->abstrak_ta));

        if ($wordCount > 500) {
            return back()
                ->withErrors([
                    'abstrak_ta' => "Abstrak maksimal 500 kata. Saat ini: $wordCount kata."
                ])
                ->withInput();
        }


        // dd($request->all());

        $perguruan_tinggi = ProfilPt::first();

        // Define variable
        $id_reg = auth()->user()->fk_id;

        $semester_aktif = SemesterAktif::first();

        $riwayat_pendidikan = RiwayatPendidikan::with('biodata', 'biodata.wilayah.kab_kota')
                    ->where('id_registrasi_mahasiswa', $id_reg)
                    ->first();

        if($request->id_wilayah) {
            $wilayah = Wilayah::where('id_wilayah', $request->id_wilayah)->first();
        }else{
            $wilayah = Wilayah::where('id_wilayah', $riwayat_pendidikan->biodata->id_wilayah)->first();
        }

        // dd($wilayah);

        $akm = AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa', $id_reg)
                ->whereRaw('RIGHT(id_semester, 1) != ?', [3])
                ->orderBy('id_semester', 'desc')
                ->first();

                // dd($akm);

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
        // dd($akm);
        $ipk = TranskripMahasiswa::where('id_registrasi_mahasiswa', $id_reg)
                ->whereNot('nilai_huruf', 'F')
                ->get();

        // dd($aktivitas, $riwayat_pendidikan);

        $ipk_total = 0;
        $sks_total = 0;

        foreach ($ipk as $nilai) {
            $ipk_total += $nilai->nilai_indeks * $nilai->sks_mata_kuliah;
            $sks_total += $nilai->sks_mata_kuliah;
        }

        $ipk = $sks_total ? round($ipk_total / $sks_total, 2) : 0;

        // dd($ipk, $ipk_total, $sks_total, $ipk);

        if($request->wisuda_ke == '0') {
            return redirect()->back()->with('error',
                'Tidak ada periode Wisuda yang tersedia !!');
        }

        // dd($request->wisuda_ke);

        // Generate file name
        $pasFotoName = 'pas_foto_' . str_replace(' ', '_', $riwayat_pendidikan->nim) . '.' . $request->file('pas_foto')->getClientOriginalExtension();
        $abstrakName = 'abstrak_' . str_replace(' ', '_', $riwayat_pendidikan->nim) . '.' . $request->file('abstrak_file')->getClientOriginalExtension();
        // $abstrakEngName = 'abstrak_eng_' . str_replace(' ', '_', $riwayat_pendidikan->nim) . '.' . $request->file('abstrak_file_eng')->getClientOriginalExtension();
        $ijazahName = 'ijazah_terakhir_' . str_replace(' ', '_', $riwayat_pendidikan->nim) . '.' . $request->file('ijazah_terakhir_file')->getClientOriginalExtension();

        // Simpan file ke folder public/storage/wisuda/abstrak, wisuda/pas_foto, dan wisuda/ijazah

        // Pastikan folder tujuan ada, jika belum maka buat foldernya
        $pasFotoDir = storage_path('app/public/wisuda/pas_foto');
        $abstrakDir = storage_path('app/public/wisuda/abstrak');
        $ijazahDir = storage_path('app/public/wisuda/ijazah');

        if (!file_exists($pasFotoDir)) {
            mkdir($pasFotoDir, 0775, true);
        }
        if (!file_exists($abstrakDir)) {
            mkdir($abstrakDir, 0775, true);
        }
        if (!file_exists($ijazahDir)) {
            mkdir($ijazahDir, 0775, true);
        }

        $pasFotoPath = $request->file('pas_foto')->storeAs('wisuda/pas_foto', $pasFotoName, 'public');
        $abstrakPath = $request->file('abstrak_file')->storeAs('wisuda/abstrak', $abstrakName, 'public');
        // $abstrakEngPath = $request->file('abstrak_file_eng')->storeAs('wisuda/abstrak', $abstrakEngName, 'public');
        $ijazahPath = $request->file('ijazah_terakhir_file')->storeAs('wisuda/ijazah', $ijazahName, 'public');

        // Simpan path ke database
        $pas_foto = $pasFotoPath;
        $abstrak_file = 'storage/' . $abstrakPath;
        // $abstrak_file_eng = 'storage/' . $abstrakEngPath;
        $ijazah_terakhir_file = 'storage/' . $ijazahPath;

        // dd($pas_foto, $abstrak_file, $abstrak_file_eng, $ijazah_terakhir_file);

        // Cek apakah file berhasil diupload
        if (!$pasFotoPath) {
            return redirect()->back()->with('error', 'Pas foto gagal diunggah. Silakan coba lagi.');
        }

        if (!$abstrakPath) {
            return redirect()->back()->with('error', 'File abstrak gagal diunggah. Silakan coba lagi.');
        }

        // if (!$abstrakEngPath) {
        //     return redirect()->back()->with('error', 'File abstrak (English) gagal diunggah. Silakan coba lagi.');
        // }

        if (!$ijazahPath) {
            return redirect()->back()->with('error', 'File ijazah terakhir gagal diunggah. Silakan coba lagi.');
        }

        // dd($abstrakPath, $abstrak_file);
        // dd($request->all());

        try {
            DB::beginTransaction();

            $wisuda = Wisuda::create([
            'id_perguruan_tinggi' => $perguruan_tinggi->id_perguruan_tinggi,
            'id_registrasi_mahasiswa' => $id_reg,
            'id_prodi' => $riwayat_pendidikan->id_prodi,
            'tgl_masuk' => $riwayat_pendidikan->tanggal_daftar,
            'wisuda_ke' => $request->wisuda_ke,
            'sks_diakui' => $akm->sks_total,
            'ipk' => $ipk,
            'id_aktivitas' => $aktivitas->id_aktivitas,
            'angkatan' => $akm->angkatan,
            'nim' => $riwayat_pendidikan->nim,
            'nama_mahasiswa' => $riwayat_pendidikan->nama_mahasiswa,
            'kosentrasi' => $request->kosentrasi,
            'tgl_sk_pembimbing' => $aktivitas->tanggal_sk_tugas,
            'no_sk_pembimbing' => $aktivitas->sk_tugas,
            'pas_foto' => $pas_foto,
            'lokasi_kuliah' => $request->lokasi_kuliah,
            'judul_eng' => strtoupper($request->judul_eng),
            'abstrak_ta' => $request->abstrak_ta,
            'abstrak_file' => $abstrak_file,
            // 'abstrak_file_eng' => $abstrak_file_eng,
            'ijazah_terakhir_file' => $ijazah_terakhir_file,
            'id_bku_prodi' => $request->bku_prodi,
            'approved' => 0,
            ]);

            // dd($wisuda);

            // Update biodata_mahasiswas table
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

            // dd($request->all());

            // Redirect kembali ke halaman index dengan pesan sukses
            return redirect()->route('mahasiswa.wisuda.index')->with('success', 'Data Berhasil di Tambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Wisuda store error: '.$e->getMessage());
            // Handle exception
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data!');
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
    
}
