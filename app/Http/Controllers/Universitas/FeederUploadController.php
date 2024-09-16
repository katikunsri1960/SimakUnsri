<?php

namespace App\Http\Controllers\Universitas;

use App\Http\Controllers\Controller;
use App\Models\KuisonerAnswer;
use App\Models\Perkuliahan\AktivitasKuliahMahasiswa;
use App\Models\Perkuliahan\DosenPengajarKelasKuliah;
use App\Models\Perkuliahan\KelasKuliah;
use App\Models\Perkuliahan\KomponenEvaluasiKelas;
use App\Models\Perkuliahan\NilaiKomponenEvaluasi;
use App\Models\Perkuliahan\NilaiPerkuliahan;
use App\Models\Perkuliahan\PesertaKelasKuliah;
use App\Models\Perkuliahan\RencanaPembelajaran;
use App\Models\ProgramStudi;
use App\Models\Semester;
use App\Models\SemesterAktif;
use App\Services\Feeder\FeederUpload;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FeederUploadController extends Controller
{

    public function akm()
    {
        $semesterAktif = SemesterAktif::first();
        // $count = AktivitasKuliahMahasiswa::where('feeder', 0)->count();
        $prodi = ProgramStudi::where('status', 'A')->orderBy('kode_program_studi')->get();
        // $angkatan = AktivitasKuliahMahasiswa::select('angkatan')->distinct()->orderBy('angkatan', 'desc')->get();
        $semester = Semester::select('nama_semester', 'id_semester')->where('id_semester', '<=', $semesterAktif->id_semester)->orderBy('id_semester', 'desc')->get();
        // $status_mahasiswa = AktivitasKuliahMahasiswa::select('id_status_mahasiswa', 'nama_status_mahasiswa')->distinct()->orderBy('id_status_mahasiswa')->get();
        return view('universitas.feeder-upload.akm.index',
        [
            // 'count' => $count,
            'prodi' => $prodi,
            // 'angkatan' => $angkatan,
            'semester' => $semester,
            'semesterAktif' => $semesterAktif,
            // 'status_mahasiswa' => $status_mahasiswa
        ]);
    }

    public function akm_data(Request $request)
    {
        $prodi = ProgramStudi::find($request->id_prodi)->id_prodi;
        $data = AktivitasKuliahMahasiswa::leftJoin('pembiayaans', 'aktivitas_kuliah_mahasiswas.id_pembiayaan', 'pembiayaans.id_pembiayaan')->where('feeder', 0)
            ->where('id_semester', $request->id_semester)
            ->where('id_prodi', $prodi)
            ->get();

        return response()->json($data);
    }

    public function upload_akm_ajax(Request $request)
    {
        // Start the upload process and return a response immediately
        // The actual progress updates will be handled by the uploadAkmProgress method
        // Log::info('Request data in upload_akm:', $request->all());
        return response()->json(['message' => 'Upload started']);
    }


    public function upload_akm(Request $request)
    {

        $prodi = ProgramStudi::find($request->prodi)->id_prodi;

        $semester = $request->semester;

        // return response()->json(['message' => $semester.' - '.$prodi]);

        $data = AktivitasKuliahMahasiswa::where('feeder', 0)
            ->where('id_semester', $semester)
            ->where('id_prodi', $prodi)
            // ->whereNotNull('id_pembiayaan')
            ->get();

        $totalData = $data->count();

        if ($totalData == 0) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        $act = 'InsertPerkuliahanMahasiswa';
        $actGet = 'GetAktivitasKuliahMahasiswa';
        $dataGagal = 0;
        $dataBerhasil = 0;

        $response = new StreamedResponse(function () use ($data, $totalData, $act, $actGet, &$dataGagal, &$dataBerhasil) {
            foreach ($data as $index => $d) {
                $record = [
                    'id_registrasi_mahasiswa' => $d->id_registrasi_mahasiswa,
                    'id_semester' => $d->id_semester,
                    'id_status_mahasiswa' => $d->id_status_mahasiswa,
                    'ips' => $d->ips,
                    'ipk' => $d->ipk,
                    'sks_semester' => $d->sks_semester,
                    'total_sks' => $d->sks_total,
                    'biaya_kuliah_smt' => $d->biaya_kuliah_smt,
                    'id_pembiayaan' => $d->id_pembiayaan,
                ];

                $recordGet = "id_registrasi_mahasiswa = '".$d->id_registrasi_mahasiswa."' AND id_semester = '".$d->id_semester."'";

                $req = new FeederUpload($act, $record, $actGet, $recordGet);
                $result = $req->uploadAkm();

                if (isset($result['error_code']) && $result['error_code'] == 0) {
                    $d->update(['feeder' => 1]);
                    $dataBerhasil++;
                } else {
                    $d->update(
                        [
                            'status_sync' => $result['error_desc'],
                        ]
                        );
                    $dataGagal++;
                }

                // Send progress update
                $progress = ($index + 1) / $totalData * 100;
                echo "data: " . json_encode(['progress' => $progress, 'dataBerhasil' => $dataBerhasil, 'dataGagal' => $dataGagal]) . "\n\n";
                ob_flush();
                flush();
            }
        });

        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->headers->set('Connection', 'keep-alive');

        return $response;

    }

    public function rps()
    {
        $prodi = ProgramStudi::where('status', 'A')->orderBy('kode_program_studi')->get();

        return view('universitas.feeder-upload.mata-kuliah.rps', [
            'prodi' => $prodi
        ]);
    }

    public function rps_data(Request $request)
    {
        $prodi = ProgramStudi::find($request->id_prodi)->id_prodi;
        $data = RencanaPembelajaran::where('feeder', 0)
                ->where('id_prodi', $prodi)
                ->where('approved', 1)
                ->orderBy('id_matkul')
                ->orderBy('pertemuan')
                ->get();

        return response()->json($data);
    }

    public function rps_upload(Request $request)
    {
        $prodi = ProgramStudi::find($request->prodi)->id_prodi;

        // $semester = $request->semester;

        // return response()->json(['message' => $semester.' - '.$prodi]);

        $data = RencanaPembelajaran::where('feeder', 0)
                ->where('approved', 1)
                ->where('id_prodi', $prodi)
                // ->whereNotNull('id_pembiayaan')
                ->get();

        $totalData = $data->count();

        if ($totalData == 0) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        $act = 'InsertRencanaPembelajaran';
        $actGet = 'GetListRencanaPembelajaran';
        $dataGagal = 0;
        $dataBerhasil = 0;

        $response = new StreamedResponse(function () use ($data, $totalData, $act, $actGet, &$dataGagal, &$dataBerhasil) {
            foreach ($data as $index => $d) {
                $record = [
                    "id_matkul" => $d->id_matkul,
                    "pertemuan" => $d->pertemuan,
                    "materi_indonesia" => $d->materi_indonesia,
                    "materi_inggris" => $d->materi_inggris,
                ];

                $recordGet = "id_registrasi_mahasiswa = '".$d->id_registrasi_mahasiswa."' AND id_semester = '".$d->id_semester."'";

                $req = new FeederUpload($act, $record, $actGet, $recordGet);
                $result = $req->uploadRps();

                if (isset($result['error_code']) && $result['error_code'] == 0) {
                    $d->update([
                        'id_rencana_ajar' => $result['data']['id_rencana_ajar'],
                        'feeder' => 1
                    ]);
                    $dataBerhasil++;
                } else {
                    $d->update(
                            [
                                'status_sync' => $result['error_desc'],
                            ]);
                    $dataGagal++;
                }

                // Send progress update
                $progress = ($index + 1) / $totalData * 100;
                echo "data: " . json_encode(['progress' => $progress, 'dataBerhasil' => $dataBerhasil, 'dataGagal' => $dataGagal]) . "\n\n";
                ob_flush();
                flush();
            }
        });

        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->headers->set('Connection', 'keep-alive');

        return $response;
    }

    public function upload_ajax(Request $request)
    {
        // Start the upload process and return a response immediately
        // The actual progress updates will be handled by the uploadProgress method
        return response()->json(['message' => 'Upload started']);
    }


    public function kelas()
    {
        $semesterAktif = SemesterAktif::first();
        $prodi = ProgramStudi::where('status', 'A')->orderBy('kode_program_studi')->get();
        $semester = Semester::select('nama_semester', 'id_semester')->where('id_semester', '<=', $semesterAktif->id_semester)->orderBy('id_semester', 'desc')->get();

        return view('universitas.feeder-upload.perkuliahan.kelas', [
            'prodi' => $prodi,
            'semester' => $semester,
            'semesterAktif' => $semesterAktif,
        ]);
    }

    public function kelas_data(Request $request)
    {
        $prodi = ProgramStudi::find($request->id_prodi)->id_prodi;
        $data = KelasKuliah::join('mata_kuliahs as m', 'kelas_kuliahs.id_matkul', 'm.id_matkul')
                ->join('semesters as s', 'kelas_kuliahs.id_semester', 's.id_semester')
                ->select('kelas_kuliahs.*', 'm.kode_mata_kuliah as kode_matkul', 'm.nama_mata_kuliah as nama_matkul', 's.nama_semester as nm_semester')
                ->where('kelas_kuliahs.id_semester', $request->id_semester)
                ->where('kelas_kuliahs.id_prodi', $prodi)
                ->where('kelas_kuliahs.feeder', 0)
                ->get();

        return response()->json($data);
    }

    public function kelas_upload(Request $request)
    {
        $prodi = ProgramStudi::find($request->prodi)->id_prodi;

        $semester = $request->semester;

        // return response()->json(['message' => $semester.' - '.$prodi]);

        $data = KelasKuliah::join('mata_kuliahs as m', 'kelas_kuliahs.id_matkul', 'm.id_matkul')
                ->select('kelas_kuliahs.*', 'm.sks_mata_kuliah as sks_mk', 'm.sks_tatap_muka as sks_tm', 'm.sks_praktek as sks_prak', 'm.sks_praktek_lapangan as sks_prak_lap', 'm.sks_simulasi as sks_sim')
                ->where('feeder', 0)
                ->where('kelas_kuliahs.id_prodi', $prodi)
                ->where('kelas_kuliahs.id_semester', $semester)
                // ->whereIn('kelas_kuliahs.id_matkul', ['685adb41-9f6a-4dde-9f67-8083ba38a559'])
                // ->where('kelas_kuliahs.id_kelas_kuliah', '4e24a587-aff5-466c-badf-a1ef6187043d')
                ->get();

        $totalData = $data->count();
        // dd($data);
        if ($totalData == 0) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        $act = 'InsertKelasKuliah';
        $actGet = 'GetDetailKelasKuliah';
        $dataGagal = 0;
        $dataBerhasil = 0;

        $response = new StreamedResponse(function () use ($data, $totalData, $act, $actGet, &$dataGagal, &$dataBerhasil) {
            foreach ($data as $index => $d) {
                $id_kelas_lama = $d->id_kelas_kuliah;
                $record = [
                    "id_prodi" => $d->id_prodi,
                    "id_semester" => $d->id_semester,
                    "id_matkul" => $d->id_matkul,
                    "nama_kelas_kuliah" => $d->nama_kelas_kuliah,
                    "sks_mk" => $d->sks_mk,
                    "sks_tm" => $d->sks_tm,
                    "sks_prak" => $d->sks_prak,
                    "sks_prak_lap" => $d->sks_prak_lap,
                    "sks_sim" => $d->sks_sim,
                    "bahasan" => $d->bahasan,
                    // "a_selenggara_pditt" => '',
                    "apa_untuk_pditt" => $d->apa_untuk_pditt,
                    "kapasitas" => $d->kapasitas,
                    "tanggal_mulai_efektif" => $d->tanggal_mulai_efektif,
                    "tanggal_akhir_efektif" => $d->tanggal_akhir_efektif,
                    // "id_mou" => '',
                    "lingkup" => $d->lingkup,
                    "mode" => $d->mode,
                ];

                $recordGet = "id_kelas_kuliah = '".$d->id_kelas_kuliah."'" ;

                $req = new FeederUpload($act, $record, $actGet, $recordGet);
                $result = $req->uploadKelas();

                if (isset($result['error_code']) && $result['error_code'] == 0) {

                    DB::beginTransaction();

                    // KomponenEvaluasiKelas::where('id_kelas_kuliah', $id_kelas_lama)->update(['id_kelas_kuliah' => $result['data']['id_kelas_kuliah']]);
                    KuisonerAnswer::where('id_kelas_kuliah', $id_kelas_lama)->update(['id_kelas_kuliah' => $result['data']['id_kelas_kuliah']]);
                    NilaiPerkuliahan::where('id_kelas_kuliah', $id_kelas_lama)->update(['id_kelas_kuliah' => $result['data']['id_kelas_kuliah']]);
                    DosenPengajarKelasKuliah::where('id_kelas_kuliah', $id_kelas_lama)->update(['id_kelas_kuliah' => $result['data']['id_kelas_kuliah']]);
                    PesertaKelasKuliah::where('id_kelas_kuliah', $id_kelas_lama)->update(['id_kelas_kuliah' => $result['data']['id_kelas_kuliah']]);

                    NilaiKomponenEvaluasi::where('id_kelas', $id_kelas_lama)->update(['id_kelas' => $result['data']['id_kelas_kuliah']]);

                    $d->update([
                        'id_kelas_kuliah' => $result['data']['id_kelas_kuliah'],
                        'feeder' => 1
                    ]);



                    DB::commit();

                    $dataBerhasil++;
                } else {
                    // DB::rollback();
                    $d->update(
                            [
                                'status_sync' => $result['error_desc'],
                            ]);
                    $dataGagal++;
                }

                // Send progress update
                $progress = ($index + 1) / $totalData * 100;
                echo "data: " . json_encode(['progress' => $progress, 'dataBerhasil' => $dataBerhasil, 'dataGagal' => $dataGagal]) . "\n\n";
                ob_flush();
                flush();
            }
        });

        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->headers->set('Connection', 'keep-alive');

        return $response;
    }

    public function krs()
    {
        $semesterAktif = SemesterAktif::first();
        $prodi = ProgramStudi::where('status', 'A')->orderBy('kode_program_studi')->get();
        $semester = Semester::orderBy('id_semester', 'desc')->get();

        return view('universitas.feeder-upload.perkuliahan.krs', [
            'prodi' => $prodi,
            'semester' => $semester,
            'semesterAktif' => $semesterAktif,
        ]);
    }

    public function krs_data(Request $request)
    {
        $prodi = ProgramStudi::find($request->id_prodi)->id_prodi;
        $data = PesertaKelasKuliah::join('kelas_kuliahs as k', 'peserta_kelas_kuliahs.id_kelas_kuliah', 'k.id_kelas_kuliah')
                ->join('semesters as s', 'k.id_semester', 's.id_semester')
                ->join('riwayat_pendidikans as r', 'peserta_kelas_kuliahs.id_registrasi_mahasiswa', 'r.id_registrasi_mahasiswa')
                ->where('k.id_semester', $request->id_semester)
                ->where('k.id_prodi', $prodi)
                ->where('peserta_kelas_kuliahs.feeder', 0)
                ->where('peserta_kelas_kuliahs.approved', 1)
                ->select('peserta_kelas_kuliahs.*', 'k.nama_kelas_kuliah', 's.nama_semester', 'r.nama_program_studi as nama_program_studi_mhs')
                ->get();

        return response()->json($data);
    }

    public function krs_upload(Request $request)
    {
        $prodi = ProgramStudi::find($request->prodi)->id_prodi;

        $semester = $request->semester;

        // return response()->json(['message' => $semester.' - '.$prodi]);

        $data = PesertaKelasKuliah::join('kelas_kuliahs as k', 'peserta_kelas_kuliahs.id_kelas_kuliah', 'k.id_kelas_kuliah')
                ->where('k.id_semester', $semester)
                ->where('k.id_prodi', $prodi)
                // ->where('peserta_kelas_kuliahs.id_kelas_kuliah', 'be7c41f0-bb74-4471-b23a-488e61a30ccc')
                ->where('peserta_kelas_kuliahs.feeder', 0)
                ->where('peserta_kelas_kuliahs.approved', 1)
                ->select('peserta_kelas_kuliahs.*')
                ->get();

        $totalData = $data->count();
        // dd($data);
        if ($totalData == 0) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        $act = 'InsertPesertaKelasKuliah';
        $actGet = 'GetPesertaKelasKuliah';
        $dataGagal = 0;
        $dataBerhasil = 0;

        $response = new StreamedResponse(function () use ($data, $totalData, $act, $actGet, &$dataGagal, &$dataBerhasil) {
            foreach ($data as $index => $d) {

                $record = [
                    "id_registrasi_mahasiswa" => $d->id_registrasi_mahasiswa,
                    "id_kelas_kuliah" => $d->id_kelas_kuliah,
                ];

                $recordGet = "id_kelas_kuliah = '".$d->id_kelas_kuliah."'" ;

                $req = new FeederUpload($act, $record, $actGet, $recordGet);
                $result = $req->uploadGeneral();

                if (isset($result['error_code']) && $result['error_code'] == 0) {

                    $d->update([
                        'feeder' => 1
                    ]);

                    $dataBerhasil++;
                } else {
                    $d->update(
                            [
                                'status_sync' => $result['error_desc'],
                            ]);
                    $dataGagal++;
                }

                // Send progress update
                $progress = ($index + 1) / $totalData * 100;
                echo "data: " . json_encode(['progress' => $progress, 'dataBerhasil' => $dataBerhasil, 'dataGagal' => $dataGagal]) . "\n\n";
                ob_flush();
                flush();
            }
        });

        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->headers->set('Connection', 'keep-alive');

        return $response;
    }

    public function dosen_ajar()
    {
        $semesterAktif = SemesterAktif::first();
        $prodi = ProgramStudi::where('status', 'A')->orderBy('kode_program_studi')->get();
        $semester = Semester::select('nama_semester', 'id_semester')->where('id_semester', '<=', $semesterAktif->id_semester)->orderBy('id_semester', 'desc')->get();

        return view('universitas.feeder-upload.perkuliahan.dosen-ajar', [
            'prodi' => $prodi,
            'semester' => $semester,
            'semesterAktif' => $semesterAktif,
        ]);
    }

    public function dosen_ajar_data(Request $request)
    {
        $prodi = ProgramStudi::find($request->id_prodi)->id_prodi;
        $data = DosenPengajarKelasKuliah::join('kelas_kuliahs as k', 'k.id_kelas_kuliah', 'dosen_pengajar_kelas_kuliah.id_kelas_kuliah')
                ->where('k.id_semester', $request->id_semester)
                ->where('k.id_prodi', $prodi)
                ->where('dosen_pengajar_kelas_kuliah.feeder', 0)
                ->select('dosen_pengajar_kelas_kuliah.*')
                ->get();

        return response()->json($data);
    }

    public function dosen_ajar_upload(Request $request)
    {
        $prodi = ProgramStudi::find($request->prodi)->id_prodi;

        $semester = $request->semester;

        // return response()->json(['message' => $semester.' - '.$prodi]);

        $data = DosenPengajarKelasKuliah::join('kelas_kuliahs as k', 'k.id_kelas_kuliah', 'dosen_pengajar_kelas_kuliah.id_kelas_kuliah')
                ->where('k.id_semester', $request->id_semester)
                ->where('k.id_prodi', $prodi)
                ->where('dosen_pengajar_kelas_kuliah.feeder', 0)
                ->select('dosen_pengajar_kelas_kuliah.*')
                ->get();

        $totalData = $data->count();
        // dd($data);
        if ($totalData == 0) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        $act = 'InsertPesertaKelasKuliah';
        $actGet = 'GetPesertaKelasKuliah';
        $dataGagal = 0;
        $dataBerhasil = 0;

        $response = new StreamedResponse(function () use ($data, $totalData, $act, $actGet, &$dataGagal, &$dataBerhasil) {
            foreach ($data as $index => $d) {

                $record = [
                    'id_registrasi_dosen' => $d->id_registrasi_dosen,
                    'id_kelas_kuliah' => $d->id_kelas_kuliah,
                    'sks_substansi_total' => $d->sks_substansi_total,
/*											   'sks_tm_subst' => 0,
                    'sks_prak_subst' => 0,
                'sks_prak_lap_subst' => 0,
                    'sks_sim_subst' => 0,*/
                    'rencana_minggu_pertemuan' => $d->rencana_minggu_pertemuan,
                    'realisasi_minggu_pertemuan' => $d->tatap_muka_real,
                    'id_jenis_evaluasi' => $d->id_jenis_evaluasi,
                ];

                $recordGet = "id_kelas_kuliah = '".$d->id_kelas_kuliah."'" ;

                $req = new FeederUpload($act, $record, $actGet, $recordGet);
                $result = $req->uploadGeneral();

                if (isset($result['error_code']) && $result['error_code'] == 0) {

                    $d->update([
                        'id_aktivitas_mengajar' => $result['data']['id_aktivitas_mengajar'],
                        'feeder' => 1
                    ]);

                    $dataBerhasil++;
                } else {
                    $d->update(
                            [
                                'status_sync' => $result['error_desc'],
                            ]);
                    $dataGagal++;
                }

                // Send progress update
                $progress = ($index + 1) / $totalData * 100;
                echo "data: " . json_encode(['progress' => $progress, 'dataBerhasil' => $dataBerhasil, 'dataGagal' => $dataGagal]) . "\n\n";
                ob_flush();
                flush();
            }
        });

        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->headers->set('Connection', 'keep-alive');

        return $response;
    }

    public function komponen_evaluasi()
    {
        $semesterAktif = SemesterAktif::first();
        $prodi = ProgramStudi::where('status', 'A')->orderBy('kode_program_studi')->get();
        $semester = Semester::select('nama_semester', 'id_semester')->where('id_semester', '<=', $semesterAktif->id_semester)->orderBy('id_semester', 'desc')->get();

        return view('universitas.feeder-upload.perkuliahan.komponen-evaluasi', [
            'prodi' => $prodi,
            'semester' => $semester,
            'semesterAktif' => $semesterAktif,
        ]);
    }

    public function komponen_evaluasi_data(Request $request)
    {
        $prodi = ProgramStudi::find($request->id_prodi)->id_prodi;
        $data = KomponenEvaluasiKelas::join('kelas_kuliahs as k', 'k.id_kelas_kuliah', 'komponen_evaluasi_kelas.id_kelas_kuliah')
                ->join('program_studis as p', 'k.id_prodi', 'p.id_prodi')
                ->join('semesters as s', 'k.id_semester', 's.id_semester')
                ->join('mata_kuliahs as m', 'k.id_matkul', 'm.id_matkul')
                ->join('jenis_evaluasis as j', 'komponen_evaluasi_kelas.id_jenis_evaluasi', 'j.id_jenis_evaluasi')
                ->where('k.id_semester', $request->id_semester)
                ->where('k.id_prodi', $prodi)
                ->where('komponen_evaluasi_kelas.feeder', 0)
                ->select('komponen_evaluasi_kelas.*', 'p.nama_jenjang_pendidikan', 'p.nama_program_studi', 's.nama_semester as nama_semester', 'k.nama_kelas_kuliah as nama_kelas_kuliah',
                        'm.kode_mata_kuliah as kode_mata_kuliah', 'm.nama_mata_kuliah as nama_mata_kuliah', 'j.nama_jenis_evaluasi as nama_jenis_evaluasi')
                ->orderBy('komponen_evaluasi_kelas.id_kelas_kuliah')
                ->orderBy('komponen_evaluasi_kelas.nomor_urut')
                ->get();

        return response()->json($data);
    }

    public function komponen_evaluasi_upload(Request $request)
    {
        $prodi = ProgramStudi::find($request->prodi)->id_prodi;

        $semester = $request->semester;

        // return response()->json(['message' => $semester.' - '.$prodi]);

        $data = KomponenEvaluasiKelas::join('kelas_kuliahs as k', 'k.id_kelas_kuliah', 'komponen_evaluasi_kelas.id_kelas_kuliah')
                ->where('k.id_semester', $semester)
                ->where('k.id_prodi', $prodi)
                ->where('komponen_evaluasi_kelas.feeder', 0)
                ->select('komponen_evaluasi_kelas.*')
                ->orderBy('komponen_evaluasi_kelas.id_kelas_kuliah')
                ->orderBy('komponen_evaluasi_kelas.nomor_urut')
                ->get();

        $totalData = $data->count();
        // dd($data);
        if ($totalData == 0) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        $act = 'InsertKomponenEvaluasiKelas';
        $actGet = 'GetListKomponenEvaluasiKelas';
        $dataGagal = 0;
        $dataBerhasil = 0;

        $response = new StreamedResponse(function () use ($data, $totalData, $act, $actGet, &$dataGagal, &$dataBerhasil) {
            foreach ($data as $index => $d) {

                $record = [
                    'id_komponen_evaluasi' => $d->id_komponen_evaluasi,
                    'id_kelas_kuliah' => $d->id_kelas_kuliah,
                    'id_jenis_evaluasi' => $d->id_jenis_evaluasi,
                    'nama' => $d->nama == '-' ? '' : $d->nama,
                    'nama_inggris' => $d->nama_inggris,
                    'nomor_urut' => $d->nomor_urut,
                    'bobot_evaluasi' => $d->bobot_evaluasi*100,
                ];

                $recordGet = "id_kelas_kuliah = '".$d->id_kelas_kuliah."'" ;

                $req = new FeederUpload($act, $record, $actGet, $recordGet);

                $result = $req->uploadKomponen();

                if (isset($result['error_code']) && $result['error_code'] == 0) {

                    DB::beginTransaction();

                    $komponen_lama = $d->id_komponen_evaluasi;

                    $d->update([
                        'id_komponen_evaluasi' => $result['data']['id_komponen_evaluasi'],
                        'feeder' => 1
                    ]);

                    NilaiKomponenEvaluasi::where('id_komponen_evaluasi', $komponen_lama)->update(['id_komponen_evaluasi' => $result['data']['id_komponen_evaluasi']]);
                    DB::commit();

                    $dataBerhasil++;


                } else {

                    // DB::rollback();

                    $d->update(
                            [
                                'status_sync' => $result['error_desc'],
                            ]);
                    $dataGagal++;
                }

                // Send progress update
                $progress = ($index + 1) / $totalData * 100;
                echo "data: " . json_encode(['progress' => $progress, 'dataBerhasil' => $dataBerhasil, 'dataGagal' => $dataGagal]) . "\n\n";
                ob_flush();
                flush();
            }
        });

        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->headers->set('Connection', 'keep-alive');

        return $response;
    }
}
