<?php

namespace App\Http\Controllers\Bak;

use Carbon\Carbon;
use App\Models\Wisuda;
// use Barryvdh\DomPDF\PDF;
use App\Models\Fakultas;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\PeriodeWisuda;
use App\Models\ProfilPt;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use App\Models\PeriodeWisuda;
use App\Models\Referensi\AllPt;
use App\Models\WisudaChecklist;
use App\Models\WisudaSyaratAdm;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Connection\Usept;
use App\Models\Mahasiswa\LulusDo;
use Illuminate\Support\Facades\DB;
use App\Models\Perpus\BebasPustaka;
use App\Http\Controllers\Controller;
use App\Models\Connection\CourseUsept;
use App\Models\Perkuliahan\ListKurikulum;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Perkuliahan\AktivitasMahasiswa;
use App\Models\Perkuliahan\AnggotaAktivitasMahasiswa;
use App\Models\SemesterAktif;

class WisudaController extends Controller
{

    public function pengaturan()
    {
        $db = new PeriodeWisuda();
        $data = $db->orderBy('periode', 'desc')->get();
        $periode = $db->max('periode') + 1;

        return view('bak.wisuda.pengaturan.index', [
            'data' => $data,
            'periode' => $periode,
        ]);
    }

    public function pengaturan_store(Request $request)
    {
        $data = $request->validate([
            'periode' => 'required|integer|unique:periode_wisudas,periode',
            'tanggal_wisuda' => 'required',
            'tanggal_mulai_daftar' => 'required',
            'tanggal_akhir_daftar' => 'required',
            'is_active' => 'required|boolean',
        ]);

        if ($data['is_active']) {
            // buat semua periode wisuda yang aktif menjadi tidak aktif
            PeriodeWisuda::where('is_active', true)->update(['is_active' => false]);
        }

        if (Carbon::createFromFormat('d-m-Y', $data['tanggal_mulai_daftar'])->gt(Carbon::createFromFormat('d-m-Y', $data['tanggal_wisuda']))) {
            return redirect()->back()->withInput()->withErrors(['tanggal_mulai_daftar' => 'Tanggal mulai daftar tidak boleh lebih besar dari tanggal wisuda']);
        }

        if (Carbon::createFromFormat('d-m-Y', $data['tanggal_akhir_daftar'])->gt(Carbon::createFromFormat('d-m-Y', $data['tanggal_wisuda']))) {
            return redirect()->back()->withInput()->withErrors(['tanggal_akhir_daftar' => 'Tanggal akhir daftar tidak boleh lebih besar dari tanggal wisuda']);
        }

        if (Carbon::createFromFormat('d-m-Y', $data['tanggal_akhir_daftar'])->lt(Carbon::createFromFormat('d-m-Y', $data['tanggal_mulai_daftar']))) {
            return redirect()->back()->withInput()->withErrors(['tanggal_akhir_daftar' => 'Tanggal akhir daftar tidak boleh lebih kecil dari tanggal mulai daftar']);
        }

        $data['tanggal_wisuda'] = Carbon::createFromFormat('d-m-Y', $data['tanggal_wisuda'])->format('Y-m-d');
        $data['tanggal_mulai_daftar'] = Carbon::createFromFormat('d-m-Y', $data['tanggal_mulai_daftar'])->format('Y-m-d');
        $data['tanggal_akhir_daftar'] = Carbon::createFromFormat('d-m-Y', $data['tanggal_akhir_daftar'])->format('Y-m-d');

        PeriodeWisuda::create($data);

        return redirect()->route('bak.wisuda.pengaturan')->with('success', 'Data berhasil ditambahkan');

    }

    public function pengaturan_update(Request $request, PeriodeWisuda $periodeWisuda)
    {
        $data = $request->validate([
            'periode' => 'required|integer|unique:periode_wisudas,periode,' . $periodeWisuda->id . ',id',
            'tanggal_wisuda' => 'required',
            'tanggal_mulai_daftar' => 'required',
            'tanggal_akhir_daftar' => 'required',
            'is_active' => 'required|boolean',
        ]);

        if ($data['is_active']) {
            PeriodeWisuda::where('is_active', true)->update(['is_active' => false]);
        }


        if (Carbon::createFromFormat('d-m-Y', $data['tanggal_mulai_daftar'])->gt(Carbon::createFromFormat('d-m-Y', $data['tanggal_wisuda']))) {
            return redirect()->back()->withErrors(['tanggal_mulai_daftar' => 'Tanggal mulai daftar tidak boleh lebih besar dari tanggal wisuda']);
        }

        if (Carbon::createFromFormat('d-m-Y', $data['tanggal_akhir_daftar'])->gt(Carbon::createFromFormat('d-m-Y', $data['tanggal_wisuda']))) {
            return redirect()->back()->withErrors(['tanggal_akhir_daftar' => 'Tanggal akhir daftar tidak boleh lebih besar dari tanggal wisuda']);
        }

        if (Carbon::createFromFormat('d-m-Y', $data['tanggal_akhir_daftar'])->lt(Carbon::createFromFormat('d-m-Y', $data['tanggal_mulai_daftar']))) {
            return redirect()->back()->withErrors(['tanggal_akhir_daftar' => 'Tanggal akhir daftar tidak boleh lebih kecil dari tanggal mulai daftar']);
        }

        $data['tanggal_wisuda'] = Carbon::createFromFormat('d-m-Y', $data['tanggal_wisuda'])->format('Y-m-d');
        $data['tanggal_mulai_daftar'] = Carbon::createFromFormat('d-m-Y', $data['tanggal_mulai_daftar'])->format('Y-m-d');
        $data['tanggal_akhir_daftar'] = Carbon::createFromFormat('d-m-Y', $data['tanggal_akhir_daftar'])->format('Y-m-d');

        $periodeWisuda->update($data);

        return redirect()->route('bak.wisuda.pengaturan')->with('success', 'Data berhasil diubah');
    }

    public function pengaturan_delete(PeriodeWisuda $periodeWisuda)
    {
        $periodeWisuda->delete();

        return redirect()->route('bak.wisuda.pengaturan')->with('success', 'Data berhasil dihapus');
    }

    public function peserta()
    {
        $fakultas = Fakultas::select('id','nama_fakultas')->get();
        $prodi = ProgramStudi::where('status', 'A')->orderBy('kode_program_studi')->get();
        $periode = PeriodeWisuda::select('periode')->orderBy('periode', 'desc')->get();
        return view('bak.wisuda.peserta.index', [
            'fakultas' => $fakultas,
            'prodi' => $prodi,
            'periode' => $periode,
        ]);
    }

    public function peserta_formulir(Wisuda $id)
    {
        $riwayat = RiwayatPendidikan::with(['prodi.fakultas', 'biodata'])->where('id_registrasi_mahasiswa', $id->id_registrasi_mahasiswa)->first();
        $pt = AllPt::where('id_perguruan_tinggi', $id->id_perguruan_tinggi)->select('nama_perguruan_tinggi')->first();
        $syaratAdm = WisudaSyaratAdm::orderBy('urutan')->select('syarat')->get();
        $checklist = WisudaChecklist::orderBy('urutan')->select('checklist')->get();

        Carbon::setLocale('id');
        $now = Carbon::now()->format('d-m-Y');
        $now = Carbon::createFromFormat('d-m-Y', $now)->translatedFormat('d F Y');

        $pdf = PDF::loadview('bak.wisuda.peserta.formulir', [
            'riwayat' => $riwayat,
            'pt' => $pt,
            'data' => $id,
            'syaratAdm' => $syaratAdm,
            'checklist' => $checklist,
            'now' => $now,
         ])
         ->setPaper('legal', 'portrait');

         return $pdf->stream('Formulir_pendaftara_wisuda-'.$riwayat->nim.'.pdf');
    }

    public function peserta_data(Request $request)
    {
        $req = $request->validate([
            'periode' => 'required',
            'fakultas' => [
            'required',
                function ($attribute, $value, $fail) {
                    if ($value !== '*' && !Fakultas::where('id', $value)->exists()) {
                    $fail('Fakultas tidak valid.');
                    }
                },
            ],
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
                ->leftJoin('fakultas as f', 'f.id', 'p.fakultas_id')
                ->leftJoin('biodata_mahasiswas as b', 'b.id_mahasiswa', 'r.id_mahasiswa')
                ->leftJoin('periode_wisudas as pw', 'pw.periode', 'data_wisuda.wisuda_ke')
                ->where('pw.periode', $req['periode'])
                ->select('data_wisuda.*', 'f.nama_fakultas', 'p.nama_program_studi as nama_prodi', 'p.nama_jenjang_pendidikan as jenjang', 'b.nik as nik', 'akt.judul',
                        'b.tempat_lahir', 'jm.nama_jalur_masuk as jalur_masuk', 'b.tanggal_lahir', 'b.rt', 'b.rw', 'b.jalan', 'b.dusun', 'b.kelurahan', 'b.id_wilayah', 'b.nama_wilayah', 'b.handphone',
                        'b.email', 'b.nama_ayah', 'b.nama_ibu_kandung', 'b.alamat_orang_tua', DB::raw("DATE_FORMAT(tanggal_daftar, '%d-%m-%Y') as tanggal_daftar"));

        if ($req['prodi'] != "*") {
            $data->where('r.id_prodi', $req['prodi']);
        }

        if ($req['fakultas'] != "*") {
            $data->where('p.fakultas_id', $req['fakultas']);
        }

        // if ($req['periode'] != "*") {
        //     $data->where('data_wisuda.periode', $req['periode']);
        // }

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

    public function simpleApprove(Request $request, $id)
    {
        try {
            $wisuda = Wisuda::findOrFail($id);
           

            $riwayatPendidikan = RiwayatPendidikan::with('prodi', 'prodi.jurusan')
                        ->where('id_registrasi_mahasiswa', $wisuda->id_registrasi_mahasiswa)->first();

            $aktivitasMahasiswa = AktivitasMahasiswa::with('anggota_aktivitas', 'bimbing_mahasiswa')
                        ->where('id_aktivitas', $wisuda->id_aktivitas)->first();

            $semester_aktif = SemesterAktif::first();
            // dd($semester_aktif);
            // Validate required data
            if (!$riwayatPendidikan || !$aktivitasMahasiswa || !$semester_aktif) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data yang diperlukan tidak lengkap.',
                ]);
            }

            // Perform update and create only if no errors
            $wisuda->update([
                'approved' => 3,
            ]);

            LulusDo::create([
                'feeder'=>'0',
                'id_registrasi_mahasiswa' => $wisuda->id_registrasi_mahasiswa,
                'id_mahasiswa' => $riwayatPendidikan->id_mahasiswa,
                'id_perguruan_tinggi' => $riwayatPendidikan->id_perguruan_tinggi,
                'id_prodi' => $riwayatPendidikan->id_prodi,
                'tgl_masuk_sp' => $riwayatPendidikan->tanggal_daftar,
                'tgl_keluar' => $wisuda->tgl_sk_yudisium,
                'skhun' => NULL,
                'no_peserta_ujian' => NULL,
                'no_seri_ijazah' => in_array($riwayatPendidikan->prodi->id_jenjang_pendidikan, ['31', '32', '37']) ? NULL : '-', // Conditional logic
                'tgl_create' => now(),
                'sks_diakui' => $wisuda->sks_diakui ?? null,
                'jalur_skripsi' => NULL,
                'judul_skripsi' => $aktivitasMahasiswa->judul,
                'bln_awal_bimbingan' => NULL,
                'bln_akhir_bimbingan' => NULL,
                'sk_yudisium' => $wisuda->no_sk_yudisium,
                'tgl_sk_yudisium' => $wisuda->tgl_sk_yudisium,
                'ipk' => $wisuda->ipk,
                'sert_prof' => $riwayatPendidikan->prodi->id_jenjang_pendidikan === '31' ? NULL : NULL, // Conditional logic
                'a_pindah_mhs_asing' => $riwayatPendidikan->a_pindah_mhs_asing ?? null,
                'id_pt_asal' => $riwayatPendidikan->id_perguruan_tinggi_asal,
                'nm_pt_asal' => $riwayatPendidikan->nama_perguruan_tinggi_asal,
                'id_prodi_asal' => $riwayatPendidikan->id_prodi_asal,
                'nm_prodi_asal' => $riwayatPendidikan->nama_program_studi_asal,
                'id_jns_daftar' => $riwayatPendidikan->id_jenis_daftar,
                'id_jns_keluar' => 1,
                'id_jalur_masuk' => $riwayatPendidikan->id_jalur_daftar,
                'id_pembiayaan' => $riwayatPendidikan->id_pembiayaan,
                'id_minat_bidang' => $riwayatPendidikan->id_minat_bidang ?? null,
                'bidang_mayor' => NULL,
                'bidang_minor' => NULL,
                'biaya_masuk_kuliah' => $riwayatPendidikan->biaya_masuk,
                'namapt' => $riwayatPendidikan->nama_perguruan_tinggi,
                'id_jur' => $riwayatPendidikan->prodi->jurusan->jurusan_id,
                'nm_jns_daftar' => $riwayatPendidikan->nama_jenis_daftar,
                'nm_smt' => $riwayatPendidikan->nama_periode_masuk,
                'nim' => $riwayatPendidikan->nim,
                'nama_mahasiswa' => $riwayatPendidikan->nama_mahasiswa,
                'nama_program_studi' => $riwayatPendidikan->prodi->nama_program_studi,
                'angkatan' => $riwayatPendidikan->angkatan,
                'id_jenis_keluar' => 1,
                'nama_jenis_keluar' => 'Lulus',
                'tanggal_keluar' => $wisuda->tgl_sk_yudisium,
                'id_periode_keluar' => $semester_aktif->id_semester,
                'keterangan' => 'WISUDA ' . $wisuda->wisuda_ke,
                'no_sertifikat_profesi' => $riwayatPendidikan->prodi->id_jenjang_pendidikan === '31' ? NULL : NULL, // Conditional logic
                'status_sync' => 'belum sync',
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Pendaftaran Wisuda berhasil disetujui.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menyetujui pendaftaran wisuda: ' . $e->getMessage(),
            ]);
        }
    }

    public function approve(Request $request, Wisuda $wisuda)
    {
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

        } catch (\Throwable) {
            $useptData = [
            'score' => 0,
            'class' => 'danger',
            'status' => 'Database USEPT tidak bisa diakses, silahkan hubungi pengelola USEPT.',
            ];
        }

        if (!$bebas_pustaka || $useptData['status'] == 'Tidak memenuhi Syarat') {
            return response()->json([
            'status' => 'error',
            'message' => 'Mahasiswa belum memenuhi syarat bebas pustaka atau USEPT.',
            ]);
        }

        $lama_studi = Carbon::parse($wisuda->tgl_keluar)->diffInMonths(Carbon::parse($wisuda->tgl_masuk));

        $wisuda->update([
            'approved' => 3,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Pendaftaran Wisuda berhasil disetujui.',
        ]);
        
        return redirect()->back()->with('success', 'Pendaftaran Wisuda berhasil disetujui.');
    }

    
    public function decline(Request $request, $id)
    {
        try {
            $wisuda = Wisuda::findOrFail($id);

            $wisuda->update([
                'approved' => 99,
                'alasan_pembatalan' => $request->alasan_pembatalan ?? null,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Pendaftaran Wisuda berhasil dibatalkan.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat membatalkan pendaftaran wisuda: ' . $e->getMessage(),
            ]);
        }
    }

    

    public function registrasi_ijazah(Request $request)
    {
        return view('bak.wisuda.registrasi-ijazah.index');
    }

    public function ijazah(Request $request)
    {

        $fakultas = Fakultas::select('id', 'nama_fakultas')->get();
        $periode = PeriodeWisuda::select('periode')->orderBy('periode', 'desc')->get();
        $prodi = ProgramStudi::where('status', 'A')->select('id_prodi', 'kode_program_studi', 'nama_jenjang_pendidikan', 'nama_program_studi', 'fakultas_id')
                    ->orderBy('kode_program_studi')->get();

        return view('bak.wisuda.ijazah.index', [
            'fakultas' => $fakultas,
            'periode' => $periode,
            'prodi' => $prodi,
        ]);
    }

    public function ijazah_download_pdf(Request $request)
    {
        $fakultas = $request->input('fakultas');

        if ($fakultas == null) {
            return response()->json([
                'status' => 'error',
                'message' => 'Fakultas tidak boleh kosong',
            ]);
        }

        $periode = $request->input('periode');
        dd($periode);
        if ($periode == null) {
            return response()->json([
                'status' => 'error',
                'message' => 'Periode tidak boleh kosong',
            ]);
        }

        $prodi = $request->input('prodi');

        if($prodi == '*')
        {
            $prodi = null;
        }

        $data = Wisuda::join('riwayat_pendidikans as r', 'r.id_registrasi_mahasiswa', 'data_wisuda.id_registrasi_mahasiswa')
                ->leftJoin('program_studis as p', 'p.id_prodi', 'r.id_prodi')
                ->leftJoin('fakultas as f', 'f.id', 'p.fakultas_id')
                ->select('data_wisuda.*', 'f.nama_fakultas', 'p.nama_program_studi as nama_prodi', 'p.nama_jenjang_pendidikan as jenjang')
                ->where('data_wisuda.periode_wisuda', $periode)
                ->where('f.id', $fakultas);
        if ($prodi != null) {
            $data->where('r.id_prodi', $prodi);
        }
        $data = $data->get();

        if ($data->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan',
            ]);
        }

        $pdf = PDF::loadview('bak.wisuda.ijazah.pdf', [
            'data' => $data,
        ])
        ->setPaper('legal', 'landscape');

        return $pdf->stream('Ijazah-'.$data[0]->periode_wisuda.'.pdf');


    }

    public function transkrip(Request $request)
    {
        return view('bak.wisuda.transkrip.index');
    }

    public function album(Request $request)
    {
        return view('bak.wisuda.album.index');
    }

    public function usept(Request $request)
    {
        return view('bak.wisuda.usept.index');
    }
}
