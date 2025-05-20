<?php

namespace App\Http\Controllers\Fakultas\Akademik;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use App\Models\Wisuda;
use App\Models\FileFakultas;
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
use DragonCode\PrettyArray\Services\File;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Perkuliahan\BimbingMahasiswa;
use App\Models\Perkuliahan\AktivitasMahasiswa;
use App\Models\Perkuliahan\RevisiSidangMahasiswa;
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

        return view('fakultas.data-akademik.wisuda.index', [
            'data' => $data,
            // 'bebas_pustaka' => $bebas_pustaka,
            // 'useptData' => $useptData,
        ]);
    }

    public function approve(Request $request, Wisuda $wisuda)
    {
        // dd($wisuda);

        if (!$wisuda->sk_yudisium_file) {
            return redirect()->back()->with('error', 'SK Yudisium belum diupload! Silahkan lakukan upload SK Yudisium!');
        }

        $bebas_pustaka = BebasPustaka::where('id_registrasi_mahasiswa', $wisuda->id_registrasi_mahasiswa)->first();

        if(!$wisuda->riwayat_pendidikan->id_kurikulum ) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kurikulum Mahasiswa Belum Diatur!!',
            ]);
        }else{
            $nilai_usept_prodi = ListKurikulum::where('id_kurikulum', $wisuda->riwayat_pendidikan->id_kurikulum)->first();
        }

        // if (!$wisuda->sk_yudisium_file) {
        //     return redirect()->back()->with('error', 'File ijazah terakhir gagal diunggah. Silakan coba lagi.');
        // }

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

        // $lama_studi = Carbon::parse($wisuda->tgl_keluar)->diffInMonths(Carbon::parse($wisuda->tgl_masuk));
        // dd($lama_studi, $wisuda);

        $wisuda->update([
            // 'lama_studi'=> $lama_studi,
            'approved' => 2,
            // 'tgl_keluar' => $request->tgl_sk_yudisium,
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
        if ($request->input('upload_baru') == 1) {
            $request->validate([
                'tgl_sk_yudisium' => 'required|date',
                'tgl_yudisium' => 'required|date',
                'no_sk_yudisium' => 'required|string|max:255',
                'sk_yudisium_file' => 'required|file|mimes:pdf|max:1024',
            ]);
        } else {
            $request->validate([
                'tgl_sk_yudisium' => 'required|date',
                'tgl_yudisium' => 'required|date',
                'no_sk_yudisium' => 'required|string|max:255',
                'id' => 'required|exists:file_fakultas,id',
            ]);
            // Ambil data file dari daftar
            $file_fakultas = FileFakultas::findOrFail($request->id);
            $request->merge(['sk_yudisium_file' => $file_fakultas->dir_file]);
        }

        // dd($request->all(), $request->input('upload_baru') );

        try {
            DB::beginTransaction();

            $wisuda = Wisuda::with('prodi')->findOrFail($id);

            // Jika memilih dari daftar, gunakan file yang dipilih
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
            return redirect()->back()->with('error', 'Gagal upload SK Yudisium: ' . $e->getMessage());
        }
    }

    public function editSkYudisium(Request $request, $id)
    {
        if ($request->input('upload_baru') == 1) {
            $request->validate([
            'tgl_sk_yudisium' => 'required|date',
            'tgl_yudisium' => 'required|date',
            'no_sk_yudisium' => 'required|string|max:255',
            'sk_yudisium_file' => 'required|file|mimes:pdf|max:1024',
            ]);
        } else {
            $request->validate([
            'tgl_sk_yudisium' => 'required|date',
            'tgl_yudisium' => 'required|date',
            'no_sk_yudisium' => 'required|string|max:255',
            // id boleh null, jika null pakai data yang ada
            ]);
            if ($request->filled('id')) {
                $file_fakultas = FileFakultas::findOrFail($request->id);
                $request->merge(['sk_yudisium_file' => $file_fakultas->dir_file]);
            } else {
            // Ambil data file_fakultas dari Wisuda yang sedang diedit
                $wisuda = Wisuda::findOrFail($id);
                $file_fakultas = FileFakultas::find($wisuda->id_file_fakultas);
                if ($file_fakultas) {
                    $request->merge([
                        'sk_yudisium_file' => $file_fakultas->dir_file,
                        'id' => $file_fakultas->id,
                    ]);
                }
            }
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
            return redirect()->back()->with('error', 'Gagal edit SK Yudisium: ' . $e->getMessage());
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
            return redirect()->back()->with('error', 'Gagal hapus SK Yudisium: ' . $e->getMessage());
        }
    }



}
