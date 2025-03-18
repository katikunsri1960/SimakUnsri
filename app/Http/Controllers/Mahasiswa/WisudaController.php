<?php

namespace App\Http\Controllers\Mahasiswa;

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
use App\Models\PeriodeWisuda;
use App\Models\Perkuliahan\BimbingMahasiswa;
use App\Models\Perkuliahan\AktivitasMahasiswa;
use App\Models\Perkuliahan\AktivitasKuliahMahasiswa;

class WisudaController extends Controller
{
    public function index(Request $request)
    {
        $id_reg = auth()->user()->fk_id;

        $semester_aktif = SemesterAktif::first();

        $riwayat_pendidikan = RiwayatPendidikan::with('prodi', 'prodi.fakultas', 'prodi.jurusan')
                    ->where('id_registrasi_mahasiswa', $id_reg)
                    ->first();

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
                ->whereIn('id_jenis_aktivitas', ['2', '3', '1', '22'])
                ->first();

        // dd($aktivitas_kuliah);

        if (!$aktivitas) {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Anda tidak dapat melakukan pendaftaran wisuda, Silahkan selesaikan Aktivitas Tugas Akhir!');
        }

        $wisuda = Wisuda::where('id_registrasi_mahasiswa', $id_reg)->first();

        $bebas_pustaka = BebasPustaka::where('id_registrasi_mahasiswa', $id_reg)->first();

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

        $riwayat_pendidikan = RiwayatPendidikan::with('biodata', 'prodi', 'prodi.fakultas', 'prodi.jurusan')->where('id_registrasi_mahasiswa', $id_reg)->first();

        $semester_aktif=SemesterAktif::with('semester')->first();

        $today = Carbon::now()->toDateString();

        $wisuda_ke = PeriodeWisuda::where('tanggal_mulai_daftar', '<=', $today)
                    ->where('tanggal_akhir_daftar', '>=', $today)
                    ->where('is_active', '1')
                    ->first();

                    // dd($wisuda_ke);

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
                ->whereIn('id_jenis_aktivitas', ['2', '3', '1', '22'])
                ->first();

        // dd($wisuda_ke);

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
        // dd($asal_sekolah);

        return view('mahasiswa.wisuda.store', ['riwayat_pendidikan' => $riwayat_pendidikan, 'semester_aktif' => $semester_aktif, 'wisuda_ke' => $wisuda_ke,
                    'aktivitas' => $aktivitas, 'usept' => $useptData, 'bebas_pustaka' => $bebas_pustaka, 'asal_sekolah' => $asal_sekolah]);
    }

    public function store(Request $request)
    {
        // Validate request data
        $request->validate([
            'nik' => 'required',
            'lokasi_kuliah' => 'required',
            'wisuda_ke' => 'required',
            'kosentrasi' => 'required',
            'abstrak_ta' => 'required|max:500',
            'pas_foto' => 'required|file|mimes:jpeg,jpg,png|max:500',
            'abstrak_file' => 'required|file|mimes:pdf|max:1024',
        ]);

        $perguruan_tinggi= AllPt::where('kode_perguruan_tinggi', '001009')->first();

        // Define variable
        $id_reg = auth()->user()->fk_id;

        $semester_aktif = SemesterAktif::first();

        $riwayat_pendidikan = RiwayatPendidikan::select('*')
                    ->where('id_registrasi_mahasiswa', $id_reg)
                    ->first();

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
                ->whereIn('id_jenis_aktivitas', ['2', '3', '1', '22'])
                ->first();
        // dd($akm);
        if($request->wisuda_ke == '0') {
            return redirect()->back()->with('error',
                'Tidak ada periode Wisuda yang tersedia !!');
        }

        // $alamat = $request->jalan . ', ' . $request->dusun . ', RT-' . $request->rt . '/RW-' . $request->rw
        // . ', ' . $request->kelurahan . ', ' . $request->nama_wilayah;

        // $alamat = str_replace(', ,', ',', $alamat);

        // dd($request->wisuda_ke);

        // Generate file name
        $pasFotoName = 'pas_foto_' . str_replace(' ', '_', $riwayat_pendidikan->nim) . '.' . $request->file('pas_foto')->getClientOriginalExtension();
        $abstrakName = 'abstrak_' . str_replace(' ', '_', $riwayat_pendidikan->nim) . '.' . $request->file('abstrak_file')->getClientOriginalExtension();

        // Simpan file ke folder public/storage/wisuda/abstrak dan wisuda/pas_foto
        $pasFotoPath = $request->file('pas_foto')->storeAs('wisuda/pas_foto', $pasFotoName, 'public');
        $abstrakPath = $request->file('abstrak_file')->storeAs('wisuda/abstrak', $abstrakName, 'public');

        // Simpan path ke database
        $pas_foto = 'storage/' . $pasFotoPath;
        $abstrak_file = 'storage/' . $abstrakPath;

        // Cek apakah file berhasil diupload
        if (!$pasFotoPath) {
            return redirect()->back()->with('error', 'Pas foto gagal diunggah. Silakan coba lagi.');
        }

        if (!$abstrakPath) {
            return redirect()->back()->with('error', 'File abstrak gagal diunggah. Silakan coba lagi.');
        }

        // dd($abstrakPath, $abstrak_file);

        Wisuda::create([
            'id_perguruan_tinggi' => $perguruan_tinggi->id_perguruan_tinggi,
            'id_registrasi_mahasiswa' => $id_reg,
            'id_prodi' => $riwayat_pendidikan->id_prodi,
            'tgl_masuk' => $riwayat_pendidikan->tanggal_daftar,
            'wisuda_ke' => $request->wisuda_ke,
            'sks_diakui' => $akm->first()->sks_total,
            'id_aktivitas' => $aktivitas->id_aktivitas,
            'angkatan' => $akm->first()->sks_total,
            'nim' => $riwayat_pendidikan->nim,
            'nama_mahasiswa' => $riwayat_pendidikan->nama_mahasiswa,
            'kosentrasi' => $request->kosentrasi,
            'pas_foto' => $pas_foto,
            'lokasi_kuliah' => $request->lokasi_kuliah,
            'abstrak_ta' => $request->abstrak_ta,
            'abstrak_file' => $abstrak_file,
            'approved' => 0,
        ]);

        // Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('mahasiswa.wisuda.index')->with('success', 'Data Berhasil di Tambahkan');
    }

}
