<?php

namespace App\Http\Controllers\Fakultas\Akademik;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use App\Models\Wisuda;
use App\Models\FileFakultas;
use App\Models\ProgramStudi;
use App\Models\Semester;
use Illuminate\Http\Request;
use App\Models\PeriodeWisuda;
use App\Models\SemesterAktif;
use App\Models\AsistensiAkhir;
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
use App\Models\Mahasiswa\PengajuanCuti;
use App\Models\Perkuliahan\ListKurikulum;
use DragonCode\PrettyArray\Services\File;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Perkuliahan\BimbingMahasiswa;
use App\Models\Perkuliahan\AktivitasMahasiswa;
use App\Models\Perkuliahan\RevisiSidangMahasiswa;
use App\Models\Perkuliahan\AktivitasKuliahMahasiswa;
use App\Models\Referensi\GelarLulusan;
use App\Models\Referensi\PredikatKelulusan;
use App\Models\Perkuliahan\NilaiPerkuliahan;
use App\Models\Perkuliahan\KonversiAktivitas;
use App\Models\Perkuliahan\NilaiTransferPendidikan;

use Illuminate\Support\Facades\Validator;


class WisudaController extends Controller
{
    public function index()
    {
        $id_fak = auth()->user()->fk_id;
        
        $prodi = ProgramStudi::where('fakultas_id', auth()->user()->fk_id)
            ->orderBy('id_jenjang_pendidikan')
            ->where('status', 'A')
            // ->where('kode_program_studi', '=', '54241')
            ->orderBy('nama_program_studi')
            ->get();

        // $prodi = ProgramStudi::where('status', 'A')->orderBy('kode_program_studi')->get();
        $periode = PeriodeWisuda::select('periode')
                ->orderBy('periode', 'desc')
                ->get();

        $gelar_lulusan = GelarLulusan::with('prodi')
                ->join('program_studis', 'program_studis.id_prodi', '=', 'gelar_lulusans.id_prodi')
                ->whereIn('gelar_lulusans.id_prodi', $prodi->pluck('id_prodi'))
                ->orderBy('program_studis.id_jenjang_pendidikan', 'asc')
                ->orderBy('program_studis.nama_program_studi', 'asc')
                ->select('gelar_lulusans.*')
                ->get();


        $predikat_lulusan = PredikatKelulusan::get();
        // dd($gelar_lulusan);

        return view('fakultas.data-akademik.wisuda.index', [
            'prodi' => $prodi,
            'periode' => $periode,
            'gelar_lulusan' => $gelar_lulusan,
            'predikat_lulusan' => $predikat_lulusan,
        ]);
    }


    // public function index(Request $request)
    // {
    //     $prodi_fak = ProgramStudi::where('fakultas_id', auth()->user()->fk_id)
    //         ->orderBy('id_jenjang_pendidikan')
    //         ->orderBy('nama_program_studi')
    //         ->get();
            
    //     $periode = PeriodeWisuda::select('periode')->orderBy('periode', 'desc')->get();
        
    //     $id_fak = auth()->user()->fk_id;

    //     $semester_aktif = SemesterAktif::first();

    //     $data = Wisuda::with('prodi', 'prodi.fakultas', 'prodi.jurusan', 'riwayat_pendidikan')
    //             ->whereHas('prodi', function ($query) use ($id_fak) {
    //                 $query->where('fakultas_id', $id_fak);
    //             })
    //             ->get();

    //     foreach ($data as $d) {
    //         $riwayat_pendidikan = RiwayatPendidikan::where('id_registrasi_mahasiswa', $d->id_registrasi_mahasiswa)->first();

    //         $bebas_pustaka = BebasPustaka::where('id_registrasi_mahasiswa', $d->id_registrasi_mahasiswa)->first();

    //         if(!$d->riwayat_pendidikan->id_kurikulum ) {
    //             return response()->json([
    //                 'status' => 'error',
    //                 'message' => 'Kurikulum Mahasiswa Belum Diatur!!',
    //             ]);
    //         }else{
    //             $nilai_usept_prodi = ListKurikulum::where('id_kurikulum', $d->riwayat_pendidikan->id_kurikulum)->first();
    //         }

    //         try {
    //             set_time_limit(10);

    //             $nilai_usept_mhs = Usept::whereIn('nim', [$riwayat_pendidikan->nim, $riwayat_pendidikan->biodata->nik])->pluck('score');
    //             $nilai_course = CourseUsept::whereIn('nim', [$riwayat_pendidikan->nim, $riwayat_pendidikan->biodata->nik])->get()->pluck('konversi');

    //             $all_scores = $nilai_usept_mhs->merge($nilai_course);
    //             $usept = $all_scores->max();

    //             $useptData = [
    //                 'score' => $usept,
    //                 'class' => $usept < $nilai_usept_prodi->nilai_usept ? 'danger' : 'success',
    //                 'status' => $usept < $nilai_usept_prodi->nilai_usept ? 'Tidak memenuhi Syarat' : 'Memenuhi Syarat',
    //         ];

    //         } catch (\Throwable) {
    //             $useptData = [
    //                 'score' => 0,
    //                 'class' => 'danger',
    //                 'status' => 'Database USEPT tidak bisa diakses, silahkan hubungi pengelola USEPT.',
    //             ];
    //         }

    //         $d->bebas_pustaka = $bebas_pustaka;
    //         $d->useptData = $useptData;
    //     }
    //     // dd($data, $useptData);

    //     return view('fakultas.data-akademik.wisuda.index', [
    //         'data' => $data,
    //         'prodi' => $prodi_fak,
    //         'periode' => $periode,
    //         // 'bebas_pustaka' => $bebas_pustaka,
    //         // 'useptData' => $useptData,
    //     ]);
    // }

    public function peserta_data(Request $request)
    {
        // Validasi
        $req = $request->validate([
            'periode' => 'required',
            'prodi' => [
                'required',
                function ($attribute, $value, $fail) {
                    if ($value !== '*' && !ProgramStudi::where('id_prodi', $value)->exists()) {
                        $fail('Program Studi tidak valid.');
                    }
                },
            ],
        ]);

        $data = Wisuda::join('riwayat_pendidikans as r', 'r.id_registrasi_mahasiswa', 'data_wisuda.id_registrasi_mahasiswa')
                ->leftJoin('aktivitas_mahasiswas as akt', 'akt.id_aktivitas', 'data_wisuda.id_aktivitas')
                ->leftJoin('program_studis as p', 'p.id_prodi', 'r.id_prodi')
                ->leftJoin('jalur_masuks as jm', 'jm.id_jalur_masuk', 'r.id_jalur_daftar')
                ->leftJoin('gelar_lulusans as g', 'g.id', 'data_wisuda.id_gelar_lulusan')
                ->leftJoin('biodata_mahasiswas as b', 'b.id_mahasiswa', 'r.id_mahasiswa')
                ->leftJoin('periode_wisudas as pw', 'pw.periode', 'data_wisuda.wisuda_ke')

                // ✅ JOIN BARU : file_fakultas
                ->leftJoin('file_fakultas as ff','ff.id','data_wisuda.id_file_fakultas')

                ->where('pw.periode', $req['periode'])
                ->where('p.fakultas_id', auth()->user()->fk_id)
                ->select(
                    'data_wisuda.*',
                    'p.nama_program_studi as nama_prodi',
                    'p.nama_jenjang_pendidikan as jenjang',
                    'g.gelar',
                    'b.nik as nik',
                    'akt.judul',
                    'b.tempat_lahir',
                    'jm.nama_jalur_masuk as jalur_masuk',
                    'b.tanggal_lahir',
                    'b.rt',
                    'b.rw',
                    'b.jalan',
                    'b.dusun',
                    'b.kelurahan',
                    'b.id_wilayah',
                    'b.nama_wilayah',
                    'b.handphone',
                    'b.email',
                    'b.nama_ayah',
                    'b.nama_ibu_kandung',
                    'b.alamat_orang_tua',

                    // Tambahkan data file SK Yudisium
                    'ff.nama_file as sk_nama_file',
                    'ff.tgl_surat as sk_tgl_surat',
                    'ff.tgl_kegiatan as sk_tgl_kegiatan',
                    'ff.dir_file as sk_file_path',

                    DB::raw("DATE_FORMAT(tanggal_daftar, '%d-%m-%Y') as tanggal_daftar")
                );

        if ($req['prodi'] != "*") {
            $data->where('r.id_prodi', $req['prodi']);
        }

        $data = $data->get();

        if ($data->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan',
                'data' => [],
            ]);
        }

        $nimList = $data->pluck('nim')->filter()->unique();
        $nikList = $data->pluck('nik')->filter()->unique();

        $useptScores = Usept::whereIn('nim', $nimList->merge($nikList))
            ->pluck('score', 'nim');

        $courseScores = CourseUsept::whereIn('nim', $nimList->merge($nikList))
            ->get()
            ->groupBy('nim')
            ->map(fn ($items) => $items->max('konversi'));

        $kurikulumUsept = ListKurikulum::pluck('nilai_usept', 'id_kurikulum');

        $data->transform(function ($item) use (
            $useptScores,
            $courseScores,
            $kurikulumUsept
        ) {

            // Ambil semua kemungkinan skor USEPT
            $scores = collect([
                $useptScores[$item->nim] ?? null,
                $useptScores[$item->nik] ?? null,
                $courseScores[$item->nim] ?? null,
                $courseScores[$item->nik] ?? null,
            ])->filter();

            $nilaiUsept = $scores->max() ?? 0;

            // Ambil batas minimal USEPT prodi
            $batasUsept = $kurikulumUsept[$item->id_kurikulum] ?? 0;

            $item->useptdata = [
                'score'  => $nilaiUsept,
                'class'  => $nilaiUsept >= $batasUsept ? 'success' : 'danger',
                'status' => $nilaiUsept >= $batasUsept
                    ? 'Memenuhi Syarat'
                    : 'Tidak Memenuhi Syarat',
            ];

            return $item;
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil diambil',
            'data' => $data,
        ]);
    }

    // public function approve(Request $request, $id)
    // {
    //      dd($id);
    //         dd($request->all());
    // }



    public function approve(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $wisuda = Wisuda::findOrFail($id);

            // ✅ VALIDASI MANUAL (WAJIB UNTUK AJAX)
            $validator = Validator::make($request->all(), [
                // 'no_urut' => 'required|numeric',
                'gelar'   => 'required|exists:gelar_lulusans,id',
                'predikat' => 'required|exists:predikat_kelulusans,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => 'error',
                    'message' => implode("\n", $validator->errors()->all()),
                ], 422);
            }

            // ❗ CEK FILE FAKULTAS (INI KASUS KAMU)
            if (!$wisuda->id_file_fakultas) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'File SK Yudisium Fakultas belum diupload.',
                ], 422);
            }

            // UPDATE
            $wisuda->update([
                'approved'         => 2,
                // 'no_urut'          => $request->no_urut,
                'id_gelar_lulusan' => $request->gelar,
                'id_predikat_kelulusan' => $request->predikat,
            ]);

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'Pendaftaran Wisuda berhasil disetujui.',
            ]);

        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan sistem.',
            ], 500);
        }
    }

    public function khs_index(Request $request)
    {
        $semesters = Semester::orderBy('id_semester', 'desc')->get();
        $semesterAktif = SemesterAktif::with('semester')->first();
        return view('fakultas.data-akademik.wisuda.khs-transkrip',[
            'semesters' => $semesters,
            'semesterAktif' => $semesterAktif,
        ]);
    }

    public function khs_transkrip_data(Request $request)
    {
        $id_prodi_fak = ProgramStudi::where('fakultas_id', auth()->user()->fk_id)
                    ->pluck('id_prodi');

                    // dd($id_prodi_fak);
        // $semester = $request->semester;
        $nim = $request->nim;

        $riwayat = RiwayatPendidikan::with('dosen_pa','prodi', 'prodi.jurusan', 'prodi.fakultas')
                    ->whereHas('prodi', function($query) {
                            $query->where('status', 'A');
                        })
                    ->where('nim', $nim)
                    ->whereIn('id_prodi', $id_prodi_fak)
                    ->first();

        // dd($riwayat);
        if (!$riwayat) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data mahasiswa tidak ditemukan',
            ]);
        }

        $nilai = NilaiPerkuliahan::with('semester')
                ->where('id_registrasi_mahasiswa', $riwayat->id_registrasi_mahasiswa)
                ->orderBy('kode_mata_kuliah', 'desc')
                ->orderBy('nama_kelas_kuliah')
                ->orderBy('id_semester')
                ->get();

        $konversi = KonversiAktivitas::with(['matkul', 'semester'])->join('anggota_aktivitas_mahasiswas as ang', 'konversi_aktivitas.id_anggota', 'ang.id_anggota')
                    // ->where('id_semester', $semester)
                    ->where('ang.id_registrasi_mahasiswa', $riwayat->id_registrasi_mahasiswa)
                    ->get();

        $transfer = NilaiTransferPendidikan::with('semester')->where('id_registrasi_mahasiswa', $riwayat->id_registrasi_mahasiswa)
                    // ->where('id_semester', $semester)
                    ->get();

        $akm = AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa', $riwayat->id_registrasi_mahasiswa)
                // ->where('id_semester', $semester)
                ->orderBy('id_semester', 'desc')
                ->get();

        if($nilai->isEmpty() && $konversi->isEmpty() && $transfer->isEmpty()) {
            $response = [
                'status' => 'error',
                'message' => 'Data KHS tidak ditemukan!',
            ];
            return response()->json($response);
        }

        $response = [
            'status' => 'success',
            'message' => 'Data KRS berhasil diambil',
            'nilai' => $nilai,
            'transfer' => $transfer,
            'konversi' => $konversi,
            'riwayat' => $riwayat,
            'akm' => $akm,
        ];


        return response()->json($response);
    }


    public function decline(Request $request, $id)
    {
        // dd($request->all(), $id);
        $request->validate([
            'alasan' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $wisuda = Wisuda::findOrFail($id);

            $wisuda->update([
                'approved' => 98,
                'alasan_pembatalan' => $request->input('alasan'),
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Pendaftaran Wisuda berhasil ditolak.',
            ]);
            // return redirect()->back()->with('success', 'SK Yudisium berhasil diupload.');
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat membatalkan pendaftaran wisuda!',
            ]);
        }
    }

    // public function search(Request $request)
    // {
    //     $data = FileFakultas::with('fakultas')
    //             ->select('*')
    //             ->where('nama_file', 'like', '%'.$request->q.'%')
    //             // ->orWhere('nama_mahasiswa', 'like', '%'.$request->q.'%')
    //             ->get();

    //     return response()->json($data);
    // }

    public function search(Request $request)
    {
        // Jika ada ID → return single file
        if ($request->has('id')) {
            $data = FileFakultas::with('fakultas')->find($request->id);

            return response()->json([
                'id' => $data->id,
                'no_sk' => $data->nama_file,
                // 'no_sk' => $data->no_sk, // Jika ada kolom ini
                'tgl_sk' => $data->tgl_surat,
                'tgl_yudisium' => $data->tgl_kegiatan,
                'file_url' => asset($data->dir_file),
                'fakultas' => $data->fakultas
            ]);

        }

        // Jika search
        $q = $request->q;
        $data = FileFakultas::with('fakultas')
            ->where("nama_file", "LIKE", "%$q%")
            ->limit(20)
            ->get();

        return response()->json($data);
    }


    public function uploadSkYudisium(Request $request, $id)
    {
        // dd($request->all(), $id);
        if ($request->input('upload_baru') == 1) {
            $request->validate([
                'tgl_sk_yudisium' => 'required|date',
                'tgl_yudisium' => 'required|date',
                'no_sk_yudisium' => 'required|string|max:255',
                'sk_yudisium_file' => 'required|file|mimes:pdf|max:1024',
            ]);

            // Cek duplikasi no_sk_yudisium pada FileFakultas
            $exists = FileFakultas::where('nama_file', $request->no_sk_yudisium)->exists();
            if ($exists) {
                return redirect()->back()->with('error', 'No SK Yudisium sudah ada di daftar,<br>silahkan pilih dari daftar atau gunakan No SK lain!');
            }
        } else {
            $request->validate([
                'tgl_sk_yudisium' => 'required|date',
                'tgl_yudisium' => 'required|date',
                'no_sk_yudisium' => 'required|string|max:255',
                'id_file' => 'required|exists:file_fakultas,id',
            ]);
            // Ambil data file dari daftar
            $file_fakultas = FileFakultas::findOrFail($request->id_file);

            // Cek duplikasi no_sk_yudisium pada FileFakultas (kecuali file yang sedang dipilih)
            $exists = FileFakultas::where('nama_file', $request->no_sk_yudisium)
                ->where('id', '!=', $file_fakultas->id)
                ->exists();
            if ($exists) {
                return redirect()->back()->with('error', 'No SK Yudisium sudah ada di daftar, Silahkan pilih dari daftar atau gunakan No SK lain!');
            }

            $request->merge(['sk_yudisium_file' => $file_fakultas->dir_file]);
        }

        // dd($request->all(), $request->input('upload_baru') );

        try {
            DB::beginTransaction();

            $wisuda = Wisuda::with('prodi')->findOrFail($id);

            // Jika memilih dari daftar, gunakan file yang dipilih
            // Pastikan folder tujuan sudah ada
            $folderPath = storage_path('app/public/wisuda/sk_yudisium');
            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0775, true);
            }

            if (!$request->input('upload_baru')) {
                $file_fakultas = FileFakultas::findOrFail($request->id_file);
                $sk_yudisium_file = $file_fakultas->dir_file;
            } else {
                $file = $request->file('sk_yudisium_file');
                $skUuid = Uuid::uuid4()->toString();
                $skYudisiumPath = $file->storeAs('wisuda/sk_yudisium', $skUuid . '.' . $file->getClientOriginalExtension(), 'public');
                $sk_yudisium_file = 'storage/' . $skYudisiumPath;

                $file_fakultas = FileFakultas::create([
                    'fakultas_id' => $wisuda->prodi->fakultas_id,
                    'nama_file' => $request->no_sk_yudisium,
                    'tgl_surat' => $request->tgl_sk_yudisium,
                    'tgl_kegiatan' => $request->tgl_yudisium,
                    'dir_file' => $sk_yudisium_file,
                ]);
            }

            // dd($file_fakultas, $sk_yudisium_file);

            $lama_studi = null;
            if ($file_fakultas->tgl_kegiatan && $wisuda->tgl_masuk) {
                $lama_studi = Carbon::parse($file_fakultas->tgl_yudisium)->diffInMonths(Carbon::parse($wisuda->tgl_masuk));
            }

            $wisuda->update([
                'id_file_fakultas' => $file_fakultas->id,
                'lama_studi'=> $lama_studi,
                'tgl_keluar' => $request->tgl_yudisium,
                'no_sk_yudisium' => $request->no_sk_yudisium,
                'tgl_sk_yudisium' => $request->tgl_sk_yudisium,
                'sk_yudisium_file' => $sk_yudisium_file,
            ]);

            // dd($wisuda);

            DB::commit();

            return redirect()->back()->with('success', 'SK Yudisium berhasil diupload.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal upload SK Yudisium! Error: ' . $e->getMessage());
        }
    }

    public function editSkYudisium(Request $request, $id)
    {
        try {
            $fakultas_id = auth()->user()->fk_id;

            // ID YANG BENAR ADALAH ID WISUDA
            $wisuda = Wisuda::findOrFail($id);

            // VALIDASI DATA WAJIB
            $request->validate([
                'no_sk_yudisium'   => 'required|string|max:255',
                'tgl_sk_yudisium'  => 'required|date',
                'tgl_yudisium'     => 'required|date',
            ]);

            /**
             |--------------------------------------------------------------------------
            | MODE 1: PAKAI FILE LAMA
            |--------------------------------------------------------------------------
            */
            if (!$request->upload_baru && $request->id_file) {

                $file = FileFakultas::where('id', $request->id_file)
                    ->where('fakultas_id', $fakultas_id)
                    ->firstOrFail();

                // UPDATE DATA FILE FAKULTAS
                $file->update([
                    'nama_file'    => $request->no_sk_yudisium,
                    'tgl_surat'    => $request->tgl_sk_yudisium,
                    'tgl_kegiatan' => $request->tgl_yudisium,
                ]);

                // UPDATE DATA PADA TABEL WISUDA
                $wisuda->update([
                    'id_file_fakultas' => $file->id,
                    'no_sk_yudisium'   => $request->no_sk_yudisium,
                    'tgl_sk_yudisium'  => $request->tgl_sk_yudisium,
                    'tgl_yudisium'     => $request->tgl_yudisium,
                    'sk_yudisium_file' => $file->dir_file,
                ]);

                return back()->with('success', 'SK Yudisium berhasil diperbarui (pakai file lama).');
            }

            /**
             |--------------------------------------------------------------------------
            | MODE 2: UPLOAD FILE BARU
            |--------------------------------------------------------------------------
            */
            if ($request->upload_baru) {

                $request->validate([
                    'sk_yudisium_file' => 'required|file|mimes:pdf|max:1024',
                ]);

                // UPLOAD FILE
                $file = $request->file('sk_yudisium_file');
                $uuid = \Ramsey\Uuid\Uuid::uuid4()->toString();

                $storedPath = $file->storeAs(
                    'wisuda/sk_yudisium',
                    $uuid . '.' . $file->getClientOriginalExtension(),
                    'public'
                );

                $fileUrl = 'storage/' . $storedPath;

                // BUAT DATA FILE_FAKULTAS BARU
                $fileRecord = FileFakultas::create([
                    'fakultas_id' => $fakultas_id,
                    'nama_file'   => $request->no_sk_yudisium,
                    'tgl_surat'   => $request->tgl_sk_yudisium,
                    'tgl_kegiatan'=> $request->tgl_yudisium,
                    'dir_file'    => $fileUrl,
                ]);

                // UPDATE DATA WISUDA
                $wisuda->update([
                    'id_file_fakultas' => $fileRecord->id,
                    'no_sk_yudisium'   => $request->no_sk_yudisium,
                    'tgl_sk_yudisium'  => $request->tgl_sk_yudisium,
                    'tgl_yudisium'     => $request->tgl_yudisium,
                    'sk_yudisium_file' => $fileUrl,
                ]);

                return back()->with('success', 'SK Yudisium berhasil diperbarui (file baru).');
            }

            return back()->with('error', 'Tidak ada metode update yang dipilih.');

        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal memperbarui SK Yudisium! Error: ' . $e->getMessage());
        }
    }

    public function deleteSkYudisium($id)
    {
        DB::beginTransaction();

        try {
            $wisuda = Wisuda::findOrFail($id);

            $wisuda->update([
                'id_file_fakultas' => null,
                'no_sk_yudisium'   => null,
                'tgl_sk_yudisium'  => null,
                'tgl_keluar'       => null,
                'lama_studi'       => null,
                'sk_yudisium_file' => null,
            ]);

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'SK Yudisium berhasil dihapus.'
            ], 200);

        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menghapus SK Yudisium.',
                'debug'   => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

}
