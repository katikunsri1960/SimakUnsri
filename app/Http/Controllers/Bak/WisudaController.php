<?php

namespace App\Http\Controllers\Bak;

use Carbon\Carbon;
use App\Models\Semester;
use App\Models\Wisuda;
use App\Models\Fakultas;
use App\Models\PeriodeWisuda;
use App\Models\ProfilPt;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use App\Models\Referensi\AllPt;
use App\Models\Referensi\PejabatUniversitas;
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
use App\Models\Mahasiswa\PisnMahasiswa;
use App\Models\Perkuliahan\AktivitasMahasiswa;
use App\Models\Perkuliahan\AnggotaAktivitasMahasiswa;
use App\Models\SemesterAktif;
use App\Models\PejabatFakultas;
use App\Exports\IjazahExport;
use App\Imports\PisnMahasiswaImport;
use App\Models\Mahasiswa\BiodataMahasiswa;
use Maatwebsite\Excel\Facades\Excel;
use setasign\Fpdi\Fpdi;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;


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

    public function peserta_data_approved(Request $request)
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
                ->leftJoin('gelar_lulusans as g', 'g.id', 'data_wisuda.id_gelar_lulusan')
                ->leftJoin('lulus_dos as l', 'l.id_registrasi_mahasiswa', 'r.id_registrasi_mahasiswa')
                ->leftJoin('pisn_mahasiswas as pisn', 'pisn.id_registrasi_mahasiswa', 'r.id_registrasi_mahasiswa')
                // ✅ JOIN BARU : file_fakultas
                ->leftJoin('file_fakultas as ff','ff.id','data_wisuda.id_file_fakultas')

                ->where('pw.periode', $req['periode'])
                ->where('data_wisuda.approved', 3)
                ->select('data_wisuda.*', 'f.nama_fakultas', 'p.nama_program_studi as nama_prodi', 'p.nama_jenjang_pendidikan as jenjang', 'b.nik as nik', 'akt.judul', 'r.jenis_kelamin',
                        'g.gelar', 'g.gelar_panjang', 'pisn.penomoran_ijazah_nasional as no_ijazah', 'l.sert_prof as no_sertifikat', DB::raw("DATE_FORMAT(pw.tanggal_wisuda, '%d-%m-%Y') as tanggal_wisuda"),
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
                ->leftJoin('gelar_lulusans as g', 'g.id', 'data_wisuda.id_gelar_lulusan')
                ->leftJoin('predikat_kelulusans as pk', 'pk.id', 'data_wisuda.id_predikat_kelulusan')
                ->leftJoin('lulus_dos as l', 'l.id_registrasi_mahasiswa', 'r.id_registrasi_mahasiswa')
                ->leftJoin('pisn_mahasiswas as pisn', 'pisn.id_registrasi_mahasiswa', 'r.id_registrasi_mahasiswa')
                // ✅ JOIN BARU : file_fakultas
                ->leftJoin('file_fakultas as ff','ff.id','data_wisuda.id_file_fakultas')

                ->where('pw.periode', $req['periode'])
                // ->where('data_wisuda.approved', 3)
                ->select(
                    'data_wisuda.*',
                    // 'r.nim',                 // ✅ WAJIB
                    // 'r.id_prodi',            // ✅ WAJIB
                    'r.id_kurikulum',
                    'f.nama_fakultas',
                    'p.nama_program_studi as nama_prodi',
                    'p.nama_jenjang_pendidikan as jenjang',
                    'b.nik',
                    'akt.judul',
                    'r.jenis_kelamin',
                    'g.gelar',
                    'g.gelar_panjang',
                    'pk.indonesia as predikat_kelulusan',
                    'pisn.penomoran_ijazah_nasional as no_ijazah',
                    'l.sert_prof as no_sertifikat',
                    DB::raw("DATE_FORMAT(pw.tanggal_wisuda, '%d-%m-%Y') as tanggal_wisuda"),
                    'b.tempat_lahir',
                    'jm.nama_jalur_masuk as jalur_masuk',
                    'b.tanggal_lahir',
                    'b.rt', 'b.rw', 'b.jalan', 'b.dusun', 'b.kelurahan',
                    'b.id_wilayah', 'b.nama_wilayah',
                    'b.handphone', 'b.email',
                    'b.nama_ayah', 'b.nama_ibu_kandung',
                    'b.alamat_orang_tua',
                    DB::raw("DATE_FORMAT(tanggal_daftar, '%d-%m-%Y') as tanggal_daftar")
                );
                        
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



        $response = [
            'status' => 'success',
            'message' => 'Data berhasil diambil',
            'data' => $data,
        ];

        return response()->json($response);
    }


    public function approve(Request $request, $id)
    {
        // $request->validate([
        //     'no_urut' => 'required|integer',
        // ]);

        try {
            DB::beginTransaction();

            $wisuda = Wisuda::findOrFail($id);

            $riwayatPendidikan = RiwayatPendidikan::with('prodi', 'prodi.jurusan')
                ->where('id_registrasi_mahasiswa', $wisuda->id_registrasi_mahasiswa)
                ->first();

            $aktivitasMahasiswa = AktivitasMahasiswa::with('anggota_aktivitas', 'bimbing_mahasiswa')
                ->where('id_aktivitas', $wisuda->id_aktivitas)
                ->first();

            $semester_aktif = SemesterAktif::first();

            if (!$riwayatPendidikan || !$aktivitasMahasiswa || !$semester_aktif) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data yang diperlukan tidak lengkap.',
                ], 422);
            }

            // ❗ CEK LULUS DO DUPLIKAT
            // if (LulusDo::where('id_registrasi_mahasiswa', $wisuda->id_registrasi_mahasiswa)->exists()) {
            //     DB::rollBack();
            //     return response()->json([
            //         'status' => 'error',
            //         'message' => 'Mahasiswa ini sudah memiliki data Lulus DO.',
            //     ], 422);
            // }

            // UPDATE WISUDA
            $wisuda->update([
                'approved' => 3,
                // 'no_urut' => $request->no_urut,
            ]);
            

            // INSERT LULUS DO
            // LulusDo::create([
            //     'feeder'=>'0',
            //     'id_registrasi_mahasiswa' => $wisuda->id_registrasi_mahasiswa,
            //     'id_mahasiswa' => $riwayatPendidikan->id_mahasiswa,
            //     'id_perguruan_tinggi' => $riwayatPendidikan->id_perguruan_tinggi,
            //     'id_prodi' => $riwayatPendidikan->id_prodi,
            //     'tgl_masuk_sp' => $riwayatPendidikan->tanggal_daftar,
            //     'tgl_keluar' => $wisuda->tgl_sk_yudisium,
            //     'skhun' => NULL,
            //     'no_peserta_ujian' => NULL,
            //     'no_seri_ijazah' => in_array($riwayatPendidikan->prodi->id_jenjang_pendidikan, ['31', '32', '37']) ? NULL : '-', // Conditional logic
            //     'tgl_create' => now(),
            //     'sks_diakui' => $wisuda->sks_diakui ?? null,
            //     'jalur_skripsi' => NULL,
            //     'judul_skripsi' => $aktivitasMahasiswa->judul,
            //     'bln_awal_bimbingan' => NULL,
            //     'bln_akhir_bimbingan' => NULL,
            //     'sk_yudisium' => $wisuda->no_sk_yudisium,
            //     'tgl_sk_yudisium' => $wisuda->tgl_sk_yudisium,
            //     'ipk' => $wisuda->ipk,
            //     'sert_prof' => $riwayatPendidikan->prodi->id_jenjang_pendidikan === '31' ? NULL : NULL, // Conditional logic
            //     'a_pindah_mhs_asing' => $riwayatPendidikan->a_pindah_mhs_asing ?? null,
            //     'id_pt_asal' => $riwayatPendidikan->id_perguruan_tinggi_asal,
            //     'nm_pt_asal' => $riwayatPendidikan->nama_perguruan_tinggi_asal,
            //     'id_prodi_asal' => $riwayatPendidikan->id_prodi_asal,
            //     'nm_prodi_asal' => $riwayatPendidikan->nama_program_studi_asal,
            //     'id_jns_daftar' => $riwayatPendidikan->id_jenis_daftar,
            //     'id_jns_keluar' => 1,
            //     'id_jalur_masuk' => $riwayatPendidikan->id_jalur_daftar,
            //     'id_pembiayaan' => $riwayatPendidikan->id_pembiayaan,
            //     'id_minat_bidang' => $riwayatPendidikan->id_minat_bidang ?? null,
            //     'bidang_mayor' => NULL,
            //     'bidang_minor' => NULL,
            //     'biaya_masuk_kuliah' => $riwayatPendidikan->biaya_masuk,
            //     'namapt' => $riwayatPendidikan->nama_perguruan_tinggi,
            //     'id_jur' => $riwayatPendidikan->prodi->jurusan->jurusan_id,
            //     'nm_jns_daftar' => $riwayatPendidikan->nama_jenis_daftar,
            //     'nm_smt' => $riwayatPendidikan->nama_periode_masuk,
            //     'nim' => $riwayatPendidikan->nim,
            //     'nama_mahasiswa' => $riwayatPendidikan->nama_mahasiswa,
            //     'nama_program_studi' => $riwayatPendidikan->prodi->nama_program_studi,
            //     'angkatan' => $riwayatPendidikan->angkatan,
            //     'id_jenis_keluar' => 1,
            //     'nama_jenis_keluar' => 'Lulus',
            //     'tanggal_keluar' => $wisuda->tgl_sk_yudisium,
            //     'id_periode_keluar' => $semester_aktif->id_semester,
            //     'keterangan' => 'WISUDA ' . $wisuda->wisuda_ke,
            //     'no_sertifikat_profesi' => $riwayatPendidikan->prodi->id_jenjang_pendidikan === '31' ? NULL : NULL, // Conditional logic
            //     'status_sync' => 'belum sync',
            // ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Pendaftaran Wisuda berhasil disetujui.',
            ]);

        } catch (QueryException $e) {
            DB::rollBack();

            // ❗ KHUSUS UNIQUE CONSTRAINT
            if ($e->getCode() == '23000') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No urut wisuda sudah digunakan. Silakan pilih nomor lain.',
                ], 422);
            }

            throw $e; // lempar ulang jika bukan error unique
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ], 500);
        }
    }
    
    public function decline(Request $request, $id)
    {
        $request->validate([
            'alasan' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $wisuda = Wisuda::findOrFail($id);

            $wisuda->update([
                'approved' => 99,
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

    public function registrasi_ijazah(Request $request)
    {
        $data = PisnMahasiswa::with(['semester', 'lulus_do', 'wisuda'])->filter($request)->get();
        $semester = Semester::orderBy('id_semester', 'desc')->get();

        // dd($data[0]->lulus_do);
        return view('bak.wisuda.registrasi-ijazah.index', compact('data', 'semester'));
    }

    public function registrasi_ijazah_store(Request $request)
    {
        // dd($request->all());
        $data = $request->validate([
            'id_registrasi_mahasiswa' => 'required|exists:riwayat_pendidikans,id_registrasi_mahasiswa',
            'penomoran_ijazah_nasional' => 'required'
        ]);
//  dd($data);
        $check = LulusDo::where('id_registrasi_mahasiswa', $data['id_registrasi_mahasiswa'])->first();

        if (!$check) {
            return redirect()->back()->with('error', 'Mahasiswa belum diluluskan!!');
        }

        $check_wisuda = Wisuda::where('id_registrasi_mahasiswa', $data['id_registrasi_mahasiswa'])->first();

        if (!$check_wisuda) {
            return redirect()->back()->with('error', 'Mahasiswa belum mendaftar wisuda!!');
        }
        // dd($request->tanggal_pembayaran);
        $data['id_registrasi_mahasiswa'] = $check->id_registrasi_mahasiswa;
        $data['nim'] = $check->nim;
        $data['id_semester'] = SemesterAktif::first()->id_semester;
        $data['periode_wisuda'] = $check_wisuda->wisuda_ke;
        $data['penomoran_ijazah_nasional'] = $request->penomoran_ijazah_nasional;

        // dd($data);
        PisnMahasiswa::create($data);

        return redirect()->back()->with('success', 'Data berhasil disimpan');
    }

    public function registrasi_ijazah_update(PisnMahasiswa $idmanual, Request $request)
    {
        $data = $request->validate([
            'status' => 'required|in:0,1',
        ]);

        $idmanual->update($data);

        return redirect()->back()->with('success', 'Data berhasil diubah');
    }

    public function registrasi_ijazah_destroy(PisnMahasiswa $idmanual)
    {
        $idmanual->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }

    public function registrasi_ijazah_upload(Request $request)
    {
        $data = $request->validate([
            'file' => 'required|mimes:xls,xlsx'
        ]);

        $file = $request->file('file');
        $import = Excel::import(new PisnMahasiswaImport(), $file);

        return redirect()->back()->with('success', "Data successfully imported!");
    }

    public function get_mahasiswa(Request $request)
    {
        $db = new RiwayatPendidikan();

        $data = $db->where('nim', 'like', '%'.$request->q.'%')
                    ->orWhere('nama_mahasiswa', 'like', '%'.$request->q.'%')
                    ->orderBy('id_periode_masuk', 'desc')->get();

        return response()->json($data);
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

    public function ijazah_download_excel(Request $request)
    {
        // Ambil input
        $fakultas = $request->input('fakultas');
        $periode = $request->input('periode');
        $prodi = $request->input('prodi');

        // Validasi Fakultas
        if ($fakultas == null) {
            return response()->json([
                'status' => 'error',
                'message' => 'Fakultas tidak boleh kosong',
            ]);
        }

        // Validasi Periode
        if ($periode == null) {
            return response()->json([
                'status' => 'error',
                'message' => 'Periode tidak boleh kosong',
            ]);
        }

        // Ambil nama fakultas
        $nama_fakultas = \DB::table('fakultas')
            ->where('id', $fakultas)
            ->value('nama_fakultas');

        // Ambil nama prodi (hanya jika bukan '*')
        $nama_prodi = null;
        if ($prodi != '*') {
            $nama_prodi = \DB::table('prodi')
                ->where('id_prodi', $prodi)
                ->value('nama_program_studi');
        } else {
            $prodi = null; // prodi all
        }

        // =========================
        // QUERY EXPORT
        // =========================
        $query = Wisuda::join('riwayat_pendidikans as r', 'r.id_registrasi_mahasiswa', 'data_wisuda.id_registrasi_mahasiswa')
                ->leftJoin('program_studis as p', 'p.id_prodi', 'r.id_prodi')
                ->leftJoin('bku_program_studis as bku', 'bku.id', 'data_wisuda.id_bku_prodi')
                ->leftJoin('gelar_lulusans as g', 'g.id', 'data_wisuda.id_gelar_lulusan')
                ->leftJoin('lulus_dos as l', 'l.id_registrasi_mahasiswa', 'r.id_registrasi_mahasiswa')
                ->leftJoin('fakultas as f', 'f.id', 'p.fakultas_id')
                ->leftJoin('pisn_mahasiswas as pisn', 'pisn.id_registrasi_mahasiswa', 'r.id_registrasi_mahasiswa')
                ->leftJoin('biodata_mahasiswas as b', 'b.id_mahasiswa', 'r.id_mahasiswa')
                ->select('data_wisuda.*', 'f.nama_fakultas', 'p.nama_program_studi as nama_prodi', 'p.kode_program_studi as kode_prodi', 'p.nama_jenjang_pendidikan as jenjang', 'r.nama_mahasiswa', 'r.nim',
                        'b.tempat_lahir', 'b.tanggal_lahir', 'p.bku_pada_ijazah as is_bku', 'bku.bku_prodi_id as bku_prodi_id', 'g.gelar', 'g.gelar_panjang', 'pisn.penomoran_ijazah_nasional as no_ijazah', 'l.sert_prof as no_sertifikat')
                ->where('data_wisuda.wisuda_ke', $periode)
                ->where('f.id', $fakultas)
                ->where('approved', 3); // hanya yang sudah disetujui

        if (!empty($prodi)) {
            $query->where('prodi_id', $prodi);
        }

        $data = $query->orderBy('jenjang', 'ASC')
                    ->orderBy('r.nim', 'ASC')
                    ->get();

        // =========================
        // BANGUN NAMA FILE
        // =========================
        $fileName = 'DAFTAR_IJAZAH-'
            . str_replace(' ', '_', strtoupper($nama_fakultas));

        $fileName .= '-' . str_replace(' ', '_', strtolower($periode)) . '.xlsx';

        // Export Excel
        return Excel::download(new IjazahExport($data), $fileName);
    }


    public function ijazah_download_pdf(Request $request)
    {
        //TAMBAHKAN PENGECEKAN NO IJAZAH ATAU SERTIFIKAT KOSONG ATAU TIDAK

        $fakultas = $request->input('fakultas');
        $fakultas_id = $request->input('fakultas');
        if ($fakultas == null) {
            return response()->json([
                'status' => 'error',
                'message' => 'Fakultas tidak boleh kosong',
            ]);
        }

        $periode = $request->input('periode');
        // dd($periode);
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
                ->leftJoin('bku_program_studis as bku', 'bku.id', 'data_wisuda.id_bku_prodi')
                ->leftJoin('gelar_lulusans as g', 'g.id', 'data_wisuda.id_gelar_lulusan')
                ->leftJoin('lulus_dos as l', 'l.id_registrasi_mahasiswa', 'r.id_registrasi_mahasiswa')
                ->leftJoin('fakultas as f', 'f.id', 'p.fakultas_id')
                ->leftJoin('pisn_mahasiswas as pisn', 'pisn.id_registrasi_mahasiswa', 'r.id_registrasi_mahasiswa')
                ->leftJoin('periode_wisudas as pw', 'pw.periode', 'data_wisuda.wisuda_ke')
                ->leftJoin('biodata_mahasiswas as b', 'b.id_mahasiswa', 'r.id_mahasiswa')
                ->select('data_wisuda.*', 'f.nama_fakultas', 'p.nama_program_studi as nama_prodi', 'p.kode_program_studi as kode_prodi', 'p.nama_jenjang_pendidikan as jenjang', 'r.nama_mahasiswa', 'r.nim',
                        'b.tempat_lahir', 'b.tanggal_lahir', 'p.bku_pada_ijazah as is_bku', 'bku.bku_prodi_id as bku_prodi_id', 'g.gelar', 'g.gelar_panjang', 'pisn.penomoran_ijazah_nasional as no_ijazah', 'l.sert_prof as no_sertifikat', 'pw.tanggal_wisuda as tanggal_wisuda')
                ->where('data_wisuda.wisuda_ke', $periode)
                ->where('f.id', $fakultas)
                ->where('approved', 3); // hanya yang sudah disetujui

        if ($prodi != null) {
            $data->where('r.id_prodi', $prodi);
        }
        $data = $data->get();

        // dd($data);

        if ($data->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan',
            ]);
        }

        $kode_univ = ProfilPt::select('kode_perguruan_tinggi')->first()->kode_perguruan_tinggi ?? "Data Kosong";

        $paper_size = [0,0, 609.45, 779.53];
        $fakultas = Fakultas::select('id', 'nama_fakultas')->where('id', $fakultas)->first()->nama_fakultas;

        $fakultas = str_replace('Fakultas ', '', $fakultas);

        $rektor = PejabatUniversitas::join('pejabat_universitas_jabatans as j', 'j.id', 'pejabat_universitas.jabatan_id')
                                    ->where('j.id', 1)
                                    ->select('pejabat_universitas.nama as nama', 'pejabat_universitas.gelar_depan as gelar_depan',
                                    'pejabat_universitas.gelar_belakang as gelar_belakang', 'pejabat_universitas.nip as nip')
                                    ->first();
        
        $dekan = PejabatFakultas::where('id_jabatan', 0)
                                ->where('id_fakultas', $fakultas_id)
                                ->select('nip', 'gelar_depan', 'gelar_belakang', 'nama_dosen as nama')
                                ->first();
                                // dd($dekan);

        $pdf = PDF::loadview('bak.wisuda.ijazah.pdf', [
            'data' => $data,
            'kode_univ' => $kode_univ,
            'fakultas' => $fakultas,
            'rektor' => $rektor,
            'dekan' => $dekan,
        ])
        ->setPaper($paper_size, 'landscape');

        return $pdf->stream('Ijazah-'.$data[0]->periode_wisuda.'.pdf');
    }

    //TRANSKRIP

    // public function transkrip(Request $request)
    // {
    //     return view('bak.wisuda.transkrip.index');
    // }

    public function transkrip(Request $request)
    {
        $fakultas = Fakultas::select('id', 'nama_fakultas')->get();
        $periode = PeriodeWisuda::select('periode')->orderBy('periode', 'desc')->get();
        $prodi = ProgramStudi::where('status', 'A')->select('id_prodi', 'kode_program_studi', 'nama_jenjang_pendidikan', 'nama_program_studi', 'fakultas_id')
                    ->orderBy('kode_program_studi')->get();

        return view('bak.wisuda.transkrip.index', [
            'fakultas' => $fakultas,
            'periode' => $periode,
            'prodi' => $prodi,
        ]);
    }


    public function transkrip_download_pdf(Request $request)
    {
        $fakultas = $request->input('fakultas');
        $fakultas_id = $request->input('fakultas');
        if ($fakultas == null) {
            return response()->json([
                'status' => 'error',
                'message' => 'Fakultas tidak boleh kosong',
            ]);
        }

        $periode = $request->input('periode');
        // dd($periode);
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

        $data = Wisuda::with('transkrip_mahasiswa','aktivitas_mahasiswa', 'aktivitas_mahasiswa.bimbing_mahasiswa', 'predikat_kelulusan', 'periode_wisuda')
                ->join('riwayat_pendidikans as r', 'r.id_registrasi_mahasiswa', 'data_wisuda.id_registrasi_mahasiswa')
                ->leftJoin('program_studis as p', 'p.id_prodi', 'r.id_prodi')
                ->leftJoin('bku_program_studis as bku', 'bku.id', 'data_wisuda.id_bku_prodi')
                ->leftJoin('gelar_lulusans as g', 'g.id', 'data_wisuda.id_gelar_lulusan')
                ->leftJoin('lulus_dos as l', 'l.id_registrasi_mahasiswa', 'r.id_registrasi_mahasiswa')
                ->leftJoin('fakultas as f', 'f.id', 'p.fakultas_id')
                ->leftJoin('pisn_mahasiswas as pisn', 'pisn.id_registrasi_mahasiswa', 'r.id_registrasi_mahasiswa')
                ->leftJoin('biodata_mahasiswas as b', 'b.id_mahasiswa', 'r.id_mahasiswa')
                // ->leftJoin('transkrip_mahasiswas as t', 't.id_registrasi_mahasiswa', 'r.id_registrasi_mahasiswa')
                ->select('data_wisuda.*', 'f.nama_fakultas', 'p.nama_program_studi as nama_prodi', 'p.nama_program_studi_en as nama_prodi_en', 'p.kode_program_studi as kode_prodi', 'p.nama_jenjang_pendidikan as jenjang', 'r.nama_mahasiswa', 'r.nim',
                        'b.tempat_lahir', 'b.tanggal_lahir', 'p.bku_pada_ijazah as is_bku', 'bku.bku_prodi_id as bku_prodi_id', 'g.gelar', 'g.gelar_panjang', 
                        'pisn.penomoran_ijazah_nasional as no_ijazah', 'l.sert_prof as no_sertifikat')
                ->where('data_wisuda.wisuda_ke', $periode)
                ->where('f.id', $fakultas)
                ->where('approved', 3); // hanya yang sudah disetujui

        if ($prodi != null) {
            $data->where('r.id_prodi', $prodi);
        }
        $data = $data->get();

        // dd($data);

        if ($data->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan',
            ]);
        }

        $kode_univ = ProfilPt::select('kode_perguruan_tinggi')->first()->kode_perguruan_tinggi ?? "Data Kosong";

        $paper_size = [0, 0, 612, 792];
        $fakultas = Fakultas::select('id', 'nama_fakultas', 'nama_fakultas_eng')->where('id', $fakultas)->first();

        // $fakultas = str_replace('Fakultas ', '', $fakultas);

        $wr1 = PejabatUniversitas::join('pejabat_universitas_jabatans as j', 'j.id', 'pejabat_universitas.jabatan_id')
                                    ->where('j.id', 2)
                                    ->select('pejabat_universitas.nama as nama', 'pejabat_universitas.gelar_depan as gelar_depan',
                                    'pejabat_universitas.gelar_belakang as gelar_belakang', 'pejabat_universitas.nip as nip', 'j.nama as jabatan')
                                    ->first();
        
        $wd1 = PejabatFakultas::where('id_jabatan', 1)
                                ->where('id_fakultas', $fakultas_id)
                                ->select('nip', 'gelar_depan', 'gelar_belakang', 'nama_dosen as nama', 'nama_jabatan as jabatan')
                                ->first();
                                // dd($dekan);

        $pdf = PDF::loadview('bak.wisuda.transkrip.pdf', [
            'data' => $data,
            'kode_univ' => $kode_univ,
            'fakultas' => $fakultas,
            'wr1' => $wr1,
            'wd1' => $wd1,
        ])
        ->setPaper($paper_size, 'landscape');

        return $pdf->stream('TRANSKRIP-'.strtoupper($fakultas->nama_fakultas).'-'.$periode.'.pdf');
    }

    public function album(Request $request)
    {
        $fakultas = Fakultas::select('id', 'nama_fakultas')->get();
        $periode = PeriodeWisuda::select('periode')->orderBy('periode', 'desc')->get();
        $prodi = ProgramStudi::where('status', 'A')->select('id_prodi', 'kode_program_studi', 'nama_jenjang_pendidikan', 'nama_program_studi', 'fakultas_id')
                    ->orderBy('kode_program_studi')->get();

        return view('bak.wisuda.album.index', [
            'fakultas' => $fakultas,
            'periode' => $periode,
            'prodi' => $prodi,
        ]);
    }

    public function album_download_pdf(Request $request)
    {
        $fakultas = $request->input('fakultas');
        $fakultas_id = $request->input('fakultas');
        if ($fakultas == null) {
            return response()->json([
                'status' => 'error',
                'message' => 'Fakultas tidak boleh kosong',
            ]);
        }

        $periode = $request->input('periode');
        // dd($periode);
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

        $data = Wisuda::with('transkrip_mahasiswa','aktivitas_mahasiswa', 'aktivitas_mahasiswa.bimbing_mahasiswa', 'predikat_kelulusan', 'periode_wisuda')
                ->join('riwayat_pendidikans as r', 'r.id_registrasi_mahasiswa', 'data_wisuda.id_registrasi_mahasiswa')
                ->leftJoin('program_studis as p', 'p.id_prodi', 'r.id_prodi')
                ->leftJoin('bku_program_studis as bku', 'bku.id', 'data_wisuda.id_bku_prodi')
                ->leftJoin('gelar_lulusans as g', 'g.id', 'data_wisuda.id_gelar_lulusan')
                ->leftJoin('lulus_dos as l', 'l.id_registrasi_mahasiswa', 'r.id_registrasi_mahasiswa')
                ->leftJoin('fakultas as f', 'f.id', 'p.fakultas_id')
                ->leftJoin('biodata_mahasiswas as b', 'b.id_mahasiswa', 'r.id_mahasiswa')
                // ->leftJoin('transkrip_mahasiswas as t', 't.id_registrasi_mahasiswa', 'r.id_registrasi_mahasiswa')
                ->select('data_wisuda.*', 'f.nama_fakultas', 'p.nama_program_studi as nama_prodi', 'p.nama_program_studi_en as nama_prodi_en', 'p.kode_program_studi as kode_prodi', 'p.nama_jenjang_pendidikan as jenjang', 'r.nama_mahasiswa', 'r.nim',
                        'b.tempat_lahir', 'b.tanggal_lahir', 'p.bku_pada_ijazah as is_bku', 'bku.bku_prodi_id as bku_prodi_id', 'g.gelar', 'g.gelar_panjang', 
                        'l.no_seri_ijazah as no_ijazah', 'l.sert_prof as no_sertifikat')
                ->where('data_wisuda.wisuda_ke', $periode)
                ->where('f.id', $fakultas)
                ->where('approved', 3); // hanya yang sudah disetujui
        if ($prodi != null) {
            $data->where('r.id_prodi', $prodi);
        }
        $data = $data->get();

        // dd($data);

        if ($data->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan',
            ]);
        }

        $kode_univ = ProfilPt::select('kode_perguruan_tinggi')->first()->kode_perguruan_tinggi ?? "Data Kosong";

        $paper_size = [0, 0, 612, 792];
        $fakultas = Fakultas::select('id', 'nama_fakultas', 'nama_fakultas_eng')->where('id', $fakultas)->first();

        $periode_wisuda = PeriodeWisuda::where('periode', $periode)->first();
        $pdf = PDF::loadview('bak.wisuda.album.pdf', [
            'data' => $data,
            'periode_wisuda' => $periode_wisuda,
            'kode_univ' => $kode_univ,
            'fakultas' => $fakultas,
        ])
        ->setPaper($paper_size, 'landscape');

        return $pdf->stream('ALBUM-'.strtoupper($fakultas->nama_fakultas).'-'.$periode_wisuda->periode.'.pdf');
    }

    public function update_foto(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'pas_foto' => 'required|image|mimes:jpg,jpeg,png|max:512',
        ], [
            'pas_foto.max' => 'Ukuran pas foto maksimal 500 KB.',
        ]);

        $wisuda = Wisuda::findOrFail($request->id);

        // Hapus foto lama
        if ($wisuda->pas_foto && Storage::disk('public')->exists($wisuda->pas_foto)) {
            Storage::disk('public')->delete($wisuda->pas_foto);
        }

        // Nama file custom
        $pasFotoName = 'pas_foto_' . str_replace(' ', '_', $wisuda->nim) . '.' .
            $request->file('pas_foto')->getClientOriginalExtension();

        // Simpan foto baru (PATH BENAR)
        $path = $request->file('pas_foto')
            ->storeAs('wisuda/pas_foto', $pasFotoName, 'public');

        // Update DB
        $wisuda->update([
            'pas_foto' => $path
        ]);

        return back()->with('success', 'Foto berhasil diperbarui');
    }




    public function usept(Request $request)
    {
        return view('bak.wisuda.usept.index');
    }
}
