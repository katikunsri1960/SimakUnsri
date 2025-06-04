<?php

namespace App\Http\Controllers\Fakultas\Akademik;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use App\Models\Wisuda;
use App\Models\FileFakultas;
use App\Models\ProgramStudi;
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

class WisudaController extends Controller
{
    public function index()
    {
        $id_fak = auth()->user()->fk_id;
        
        $prodi = ProgramStudi::where('fakultas_id', auth()->user()->fk_id)
            ->orderBy('id_jenjang_pendidikan')
            ->where('status', 'A')
            ->where('kode_program_studi', '=', '54241')
            ->orderBy('nama_program_studi')
            ->get();

        // $prodi = ProgramStudi::where('status', 'A')->orderBy('kode_program_studi')->get();
        $periode = PeriodeWisuda::select('periode')->where('periode', '179')->orderBy('periode', 'desc')->get();

        $gelar_lulusan = GelarLulusan::whereIn('id_prodi', $prodi->pluck('id_prodi'))->get();
        // dd($gelar_lulusan);

        return view('fakultas.data-akademik.wisuda.index', [
            'prodi' => $prodi,
            'periode' => $periode,
            'gelar_lulusan' => $gelar_lulusan,
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
        // dd($request->all());
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
                ->leftJoin('gelar_lulusans as g', 'g.id_prodi', 'p.id_prodi')
                ->leftJoin('biodata_mahasiswas as b', 'b.id_mahasiswa', 'r.id_mahasiswa')
                ->leftJoin('periode_wisudas as pw', 'pw.periode', 'data_wisuda.wisuda_ke')
                ->where('pw.periode', $req['periode'])
                ->select('data_wisuda.*', 'p.nama_program_studi as nama_prodi', 'p.nama_jenjang_pendidikan as jenjang', 'g.gelar', 'b.nik as nik', 'akt.judul',
                        'b.tempat_lahir', 'jm.nama_jalur_masuk as jalur_masuk', 'b.tanggal_lahir', 'b.rt', 'b.rw', 'b.jalan', 'b.dusun', 'b.kelurahan', 'b.id_wilayah', 'b.nama_wilayah', 'b.handphone',
                        'b.email', 'b.nama_ayah', 'b.nama_ibu_kandung', 'b.alamat_orang_tua', DB::raw("DATE_FORMAT(tanggal_daftar, '%d-%m-%Y') as tanggal_daftar"));

        if ($req['prodi'] != "*") {
            $data->where('r.id_prodi', $req['prodi']);
        }

        // $data->addSelect('g.gelar_depan', 'g.gelar_belakang');

        $data = $data->get();

        if ($data->isEmpty()) {
            $response = [
                'status' => 'error',
                'message' => 'Data tidak ditemukan',
                'data' => [],
            ];

            return response()->json($response);
        }

        $response = [
            'status' => 'success',
            'message' => 'Data berhasil diambil',
            'data' => $data,
        ];

        return response()->json($response);
    }

    public function approve(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $wisuda = Wisuda::findOrFail($id);

            $request->validate([
                'gelar' => 'required|string',
            ]);

            if (!$wisuda->sk_yudisium_file) {
                return redirect()->back()->with('error', 'SK Yudisium belum diupload! Silahkan lakukan upload SK Yudisium!');
            }

            $bebas_pustaka = BebasPustaka::where('id_registrasi_mahasiswa', $wisuda->id_registrasi_mahasiswa)->first();

            if (!$wisuda->riwayat_pendidikan->id_kurikulum) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Kurikulum Mahasiswa Belum Diatur!!',
                ]);
            } else {
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

            } catch (\Throwable $e) {
                $useptData = [
                    'score' => 0,
                    'class' => 'danger',
                    'status' => 'Database USEPT tidak bisa diakses, silahkan hubungi pengelola USEPT.',
                ];
            }

            if (!$bebas_pustaka || $useptData['status'] == 'Tidak memenuhi Syarat') {
                DB::rollBack();
                return redirect()->back()->with('error', 'Mahasiswa belum memenuhi syarat bebas pustaka atau USEPT.');
            }

            $wisuda->update([
                'approved' => 2,
                'id_gelar_lulusan' => $request->input('gelar'),
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Pendaftaran Wisuda berhasil disetujui.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menyetujui pendaftaran wisuda!',
            ]);
        }
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
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat membatalkan pendaftaran wisuda!',
            ]);
        }
    }

    public function search(Request $request)
    {
        $data = FileFakultas::with('fakultas')
                ->select('*')
                ->where('nama_file', 'like', '%'.$request->q.'%')
                // ->orWhere('nama_mahasiswa', 'like', '%'.$request->q.'%')
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
                'id' => 'required|exists:file_fakultas,id',
            ]);
            // Ambil data file dari daftar
            $file_fakultas = FileFakultas::findOrFail($request->id);

            // Cek duplikasi no_sk_yudisium pada FileFakultas (kecuali file yang sedang dipilih)
            $exists = FileFakultas::where('nama_file', $request->no_sk_yudisium)
                ->where('id', '!=', $file_fakultas->id)
                ->exists();
            if ($exists) {
                return redirect()->back()->with('error', "No SK Yudisium sudah ada di daftar,\nSilahkan pilih dari daftar atau gunakan No SK lain!");
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
                $file_fakultas = FileFakultas::findOrFail($request->id);
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
        // dd($request->all(), $id);
        $request->validate([
            'tgl_sk_yudisium' => 'required|date',
            'tgl_yudisium' => 'required|date',
            'no_sk_yudisium' => 'required|string|max:255',
            'sk_yudisium_file' => 'required_if:upload_baru,1|file|mimes:pdf|max:1024',
            ]);
        
            // Cari file_fakultas berdasarkan id, jika tidak ada pakai dari Wisuda
            $file_fakultas = null;
            if ($request->filled('id')) {
                $file_fakultas = FileFakultas::find($request->id);
            } else {
                $file_fakultas = FileFakultas::find(optional(Wisuda::find($id))->id_file_fakultas);
            }

            if ($file_fakultas) {
                $request->merge([
                    'sk_yudisium_file' => $file_fakultas->dir_file,
                    'id' => $file_fakultas->id,
                ]);
            }

        // dd($request->all(), $request->input('id'), $file_fakultas , $id );

        try {
            DB::beginTransaction();
            $wisuda = Wisuda::with('prodi')->findOrFail($id);

            if (!$request->input('upload_baru')) {
                $file_fakultas = FileFakultas::findOrFail($request->id);
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

            // dd($file_fakultas, $wisuda, $sk_yudisium_file, $id);

            $lama_studi = null;
            if ($file_fakultas->tgl_kegiatan && $wisuda->tgl_masuk) {
                $lama_studi = Carbon::parse($file_fakultas->tgl_yudisium)->diffInMonths(Carbon::parse($wisuda->tgl_masuk));
            }

            // dd($lama_studi, $file_fakultas, $sk_yudisium_file);

            $wisuda->update([
                'id_file_fakultas' => $file_fakultas->id,
                'lama_studi'=> $lama_studi,
                'tgl_keluar' => $request->tgl_yudisium,
                'no_sk_yudisium' => $request->no_sk_yudisium,
                'tgl_sk_yudisium' => $request->tgl_sk_yudisium,
                'sk_yudisium_file' => $sk_yudisium_file,
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'SK Yudisium berhasil diedit.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal edit SK Yudisium!');
        }
    }

    public function deleteSkYudisium($id)
    {
        // dd($id);
        try {
            DB::beginTransaction();
            $wisuda = Wisuda::findOrFail($id);

            // Set kolom file di Wisuda menjadi null
            $wisuda->update([
                'id_file_fakultas' => null,
                'no_sk_yudisium' => null,
                'tgl_sk_yudisium' => null,
                'tgl_keluar' => null,
                'lama_studi' => null,
                'sk_yudisium_file' => null,
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'SK Yudisium berhasil dihapus.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal hapus SK Yudisium!');
        }
    }



}
