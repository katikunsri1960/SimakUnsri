<?php

namespace App\Http\Controllers\Universitas;

use App\Models\Semester;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use App\Models\SemesterAktif;
use App\Models\KuisonerAnswer;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Feeder\FeederUpload;
use App\Models\Perkuliahan\KelasKuliah;
use App\Models\Perkuliahan\UjiMahasiswa;
use App\Models\Mahasiswa\AktivitasMagang;
use App\Models\Mahasiswa\PrestasiMahasiswa;
use App\Models\Perkuliahan\BimbingMahasiswa;
use App\Models\Perkuliahan\NilaiPerkuliahan;
use App\Models\Perkuliahan\KonversiAktivitas;
use App\Models\Perkuliahan\AktivitasMahasiswa;
use App\Models\Perkuliahan\PesertaKelasKuliah;
use App\Models\Perkuliahan\RencanaPembelajaran;
use App\Models\Perkuliahan\KomponenEvaluasiKelas;
use App\Models\Perkuliahan\NilaiKomponenEvaluasi;
use App\Models\Perkuliahan\NilaiTransferPendidikan;
use App\Models\Perkuliahan\AktivitasKuliahMahasiswa;
use App\Models\Perkuliahan\DosenPengajarKelasKuliah;
use App\Models\Perkuliahan\AnggotaAktivitasMahasiswa;
use Symfony\Component\HttpFoundation\StreamedResponse;

use function PHPUnit\Framework\isNull;

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
                    // KuisonerAnswer::where('id_kelas_kuliah', $id_kelas_lama)->update(['id_kelas_kuliah' => $result['data']['id_kelas_kuliah']]);
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
        $data = DosenPengajarKelasKuliah::join('kelas_kuliahs as k', 'k.id_kelas_kuliah', 'dosen_pengajar_kelas_kuliahs.id_kelas_kuliah')
                    ->join('biodata_dosens as d', 'dosen_pengajar_kelas_kuliahs.id_dosen', 'd.id_dosen')
                    ->join('mata_kuliahs as m', 'k.id_matkul', 'm.id_matkul')
                    ->join('program_studis as p', 'k.id_prodi', 'p.id_prodi')
                    ->where('k.id_semester', $request->id_semester)
                    ->where('k.id_prodi', $prodi)
                    ->where('k.feeder', 1)
                    ->where('dosen_pengajar_kelas_kuliahs.feeder', 0)
                    ->select('dosen_pengajar_kelas_kuliahs.*', 'k.nama_semester as nama_semester', 'k.nama_kelas_kuliah as nama_kelas', 'd.nidn as nidn_dosen', 'd.nama_dosen as nama',
                            'm.kode_mata_kuliah as kode_mk', 'm.sks_mata_kuliah as sks_mk', DB::raw('CONCAT(p.nama_jenjang_pendidikan, " ", p.nama_program_studi) as prodi'))
                    ->get();

        return response()->json($data);
    }

    public function dosen_ajar_upload(Request $request)
    {
        $prodi = ProgramStudi::find($request->prodi)->id_prodi;

        $semester = $request->semester;

        // return response()->json(['message' => $semester.' - '.$prodi]);

        $data = DosenPengajarKelasKuliah::join('kelas_kuliahs as k', 'k.id_kelas_kuliah', 'dosen_pengajar_kelas_kuliahs.id_kelas_kuliah')
                ->join('biodata_dosens as d', 'dosen_pengajar_kelas_kuliahs.id_dosen', 'd.id_dosen')
                ->join('mata_kuliahs as m', 'k.id_matkul', 'm.id_matkul')
                ->join('program_studis as p', 'k.id_prodi', 'p.id_prodi')
                ->where('k.id_semester', $request->semester)
                ->where('k.id_prodi', $prodi)
                ->where('k.feeder', 1)
                ->where('dosen_pengajar_kelas_kuliahs.feeder', 0)
                ->select('dosen_pengajar_kelas_kuliahs.*')
                ->get();

        $totalData = $data->count();
        // dd($data);
        if ($totalData == 0) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        $act = 'InsertDosenPengajarKelasKuliah';
        $actGet = 'GetDosenPengajarKelasKuliah';
        $dataGagal = 0;
        $dataBerhasil = 0;

        $response = new StreamedResponse(function () use ($data, $totalData, $act, $actGet, &$dataGagal, &$dataBerhasil) {
            foreach ($data as $index => $d) {

                $record = [
                    'id_aktivitas_mengajar' => $d->id_aktivitas_mengajar,
                    'id_registrasi_dosen' => $d->id_registrasi_dosen,
                    'id_kelas_kuliah' => $d->id_kelas_kuliah,
                    'sks_substansi_total' => strval($d->sks_substansi_total),
                    'rencana_minggu_pertemuan' => strval($d->rencana_minggu_pertemuan),
                    'realisasi_minggu_pertemuan' => strval($d->realisasi_minggu_pertemuan),
                    'id_jenis_evaluasi' => $d->id_jenis_evaluasi,
                ];

                $recordGet = "id_aktivitas_mengajar = '".$d->id_aktivitas_mengajar."'" ;

                $req = new FeederUpload($act, $record, $actGet, $recordGet);

                $result = $req->uploadDosenPengajar();

                if (isset($result['error_code']) && $result['error_code'] == 0) {

                    $d->update([
                        'id_aktivitas_mengajar' => $result['data']['id_aktivitas_mengajar'],
                        'feeder' => 1
                    ]);

                    $dataBerhasil++;
                } else {
                    // $d->update(
                    //         [
                    //             'status_sync' => $result['error_desc'],
                    //         ]);
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

                $bobot = $d->bobot_evaluasi * 100;

                $record = [
                    'id_komponen_evaluasi' => $d->id_komponen_evaluasi,
                    'id_kelas_kuliah' => $d->id_kelas_kuliah,
                    'id_jenis_evaluasi' => $d->id_jenis_evaluasi,
                    'nama' => $d->nama,
                    'nama_inggris' => $d->nama_inggris,
                    'nomor_urut' => $d->nomor_urut,
                    'bobot_evaluasi' => intval($bobot),
                ];

                $recordGet = "id_kelas_kuliah = '".$d->id_kelas_kuliah."'" ;

                $req = new FeederUpload($act, $record, $actGet, $recordGet);

                $result = $req->uploadKomponen();

                if (isset($result['error_code']) && $result['error_code'] == 0) {

                    DB::beginTransaction();

                    $komponen_lama = $d->id_komponen_evaluasi;

                    if (isset($result['data']['id_komponen_evaluasi'])) {
                        $d->update([
                            'id_komponen_evaluasi' => $result['data']['id_komponen_evaluasi'],
                            'feeder' => 1
                        ]);
                        NilaiKomponenEvaluasi::where('id_komponen_evaluasi', $komponen_lama)->update(['id_komponen_evaluasi' => $result['data']['id_komponen_evaluasi']]);
                    } else
                    {
                        $d->update([
                            'feeder' => 1
                        ]);
                    }

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

    public function nilai_komponen()
    {
        $semesterAktif = SemesterAktif::first();
        $prodi = ProgramStudi::where('status', 'A')->orderBy('kode_program_studi')->get();
        $semester = Semester::select('nama_semester', 'id_semester')->where('id_semester', '<=', $semesterAktif->id_semester)->orderBy('id_semester', 'desc')->get();

        return view('universitas.feeder-upload.perkuliahan.nilai-komponen', [
            'prodi' => $prodi,
            'semester' => $semester,
            'semesterAktif' => $semesterAktif,
        ]);
    }

    public function nilai_komponen_data(Request $request)
    {
        $prodi = ProgramStudi::find($request->id_prodi)->id_prodi;
        $data = NilaiKomponenEvaluasi::join('komponen_evaluasi_kelas as k', 'k.id_komponen_evaluasi', 'nilai_komponen_evaluasis.id_komponen_evaluasi')
                ->join('kelas_kuliahs as kk', 'kk.id_kelas_kuliah', 'k.id_kelas_kuliah')
                ->join('program_studis as p', 'kk.id_prodi', 'p.id_prodi')
                ->join('semesters as s', 'kk.id_semester', 's.id_semester')
                ->join('mata_kuliahs as m', 'kk.id_matkul', 'm.id_matkul')
                ->join('jenis_evaluasis as j', 'k.id_jenis_evaluasi', 'j.id_jenis_evaluasi')
                ->where('kk.id_semester', $request->id_semester)
                ->where('kk.id_prodi', $prodi)
                ->where('nilai_komponen_evaluasis.feeder', 0)
                ->select('nilai_komponen_evaluasis.*', 'p.nama_jenjang_pendidikan', 'p.nama_program_studi', 's.nama_semester as nama_semester', 'kk.nama_kelas_kuliah as nama_kelas_kuliah',
                        'm.kode_mata_kuliah as kode_mata_kuliah', 'm.nama_mata_kuliah as nama_mata_kuliah', 'j.nama_jenis_evaluasi as nama_jenis_evaluasi')
                ->orderBy('k.id_kelas_kuliah')
                ->orderBy('k.nomor_urut')
                ->get();

        return response()->json($data);
    }

    public function nilai_komponen_upload(Request $request)
    {
        $prodi = ProgramStudi::find($request->prodi)->id_prodi;

        $semester = $request->semester;

        // return response()->json(['message' => $semester.' - '.$prodi]);

        $data = NilaiKomponenEvaluasi::join('kelas_kuliahs as kk', 'kk.id_kelas_kuliah', 'nilai_komponen_evaluasis.id_kelas')
                ->where('kk.id_semester', $semester)
                ->where('kk.id_prodi', $prodi)
                ->where('nilai_komponen_evaluasis.feeder', 0)
                ->where('kk.feeder', 1)
                ->select('nilai_komponen_evaluasis.*')
                ->orderBy('nilai_komponen_evaluasis.id_kelas')
                ->get();

        $totalData = $data->count();
        // dd($data);
        if ($totalData == 0) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        $act = 'UpdateNilaiPerkuliahanKelasKomponenEvaluasi';
        $actGet = 'GetListKomponenEvaluasiKelas';
        $dataGagal = 0;
        $dataBerhasil = 0;

        $response = new StreamedResponse(function () use ($data, $totalData, $act, $actGet, &$dataGagal, &$dataBerhasil) {
            foreach ($data as $index => $d) {

                $nilai = $d->nilai_komp_eval != null ? $d->nilai_komp_eval : 0;

                $record = [
                    'id_komponen_evaluasi' => $d->id_komponen_evaluasi,
                    'id_registrasi_mahasiswa' => $d->id_registrasi_mahasiswa,
                    'nilai_komponen_evaluasi' => strval($nilai),
                    'id_kelas' => $d->id_kelas,
                ];

                $recordGet = "id_kelas = '".$d->id_kelas."'" ;

                $req = new FeederUpload($act, $record, $actGet, $recordGet);

                $result = $req->uploadNilaiKomponen();

                if (isset($result['error_code']) && $result['error_code'] == 0) {

                    DB::beginTransaction();

                    $d->update([
                        // 'id_komponen_evaluasi' => $result['data']['id_komponen_evaluasi'],
                        'feeder' => 1
                    ]);

                    DB::commit();

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

    public function nilai_kelas()
    {
        $semesterAktif = SemesterAktif::first();
        $prodi = ProgramStudi::where('status', 'A')->orderBy('kode_program_studi')->get();
        $semester = Semester::select('nama_semester', 'id_semester')->where('id_semester', '<=', $semesterAktif->id_semester)->orderBy('id_semester', 'desc')->get();

        return view('universitas.feeder-upload.perkuliahan.nilai-kelas', [
            'prodi' => $prodi,
            'semester' => $semester,
            'semesterAktif' => $semesterAktif,
        ]);
    }

    public function nilai_kelas_data(Request $request)
    {
        $prodi = ProgramStudi::find($request->id_prodi)->id_prodi;
        $data = NilaiPerkuliahan::join('kelas_kuliahs as k', 'k.id_kelas_kuliah', 'nilai_perkuliahans.id_kelas_kuliah')
                ->join('program_studis as p', 'k.id_prodi', 'p.id_prodi')
                ->join('semesters as s', 'k.id_semester', 's.id_semester')
                ->join('mata_kuliahs as m', 'k.id_matkul', 'm.id_matkul')
                ->where('k.id_semester', $request->id_semester)
                ->where('k.id_prodi', $prodi)
                ->where('nilai_perkuliahans.feeder', 0)
                ->select('nilai_perkuliahans.*', 'p.nama_jenjang_pendidikan', 'p.nama_program_studi', 's.nama_semester as nama_semester', 'k.nama_kelas_kuliah as nama_kelas_kuliah',
                        'm.kode_mata_kuliah as kode_mata_kuliah', 'm.nama_mata_kuliah as nama_mata_kuliah')
                ->orderBy('k.id_kelas_kuliah')
                ->get();

        return response()->json($data);
    }

    public function nilai_kelas_upload(Request $request)
    {
        $prodi = ProgramStudi::find($request->prodi)->id_prodi;

        $semester = $request->semester;

        // return response()->json(['message' => $semester.' - '.$prodi]);

        $data = NilaiPerkuliahan::join('kelas_kuliahs as k', 'k.id_kelas_kuliah', 'nilai_perkuliahans.id_kelas_kuliah')
                ->where('k.id_semester', $semester)
                ->where('k.id_prodi', $prodi)
                ->where('nilai_perkuliahans.feeder', 0)
                ->select('nilai_perkuliahans.*')
                ->orderBy('k.id_kelas_kuliah')
                ->get();

        $totalData = $data->count();
        // dd($data);
        if ($totalData == 0) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        $act = 'UpdateNilaiPerkuliahanKelas';
        $actGet = 'GetListKomponenEvaluasiKelas';
        $dataGagal = 0;
        $dataBerhasil = 0;

        $response = new StreamedResponse(function () use ($data, $totalData, $act, $actGet, &$dataGagal, &$dataBerhasil) {
            foreach ($data as $index => $d) {

                $record = [
                    'id_kelas_kuliah' => $d->id_kelas_kuliah,
                    'id_registrasi_mahasiswa' => $d->id_registrasi_mahasiswa,
                    'nilai_angka' => strval($d->nilai_angka),
                    'nilai_huruf' => strval($d->nilai_huruf),
                    'nilai_indeks' => strval($d->nilai_indeks),
                ];

                $recordGet = "id_kelas = '".$d->id_kelas."'" ;

                $req = new FeederUpload($act, $record, $actGet, $recordGet);

                $result = $req->uploadNilaiKelas();

                if (isset($result['error_code']) && $result['error_code'] == 0) {

                    DB::beginTransaction();

                    $d->update([
                        'feeder' => 1,
                        'status_sync' => 'sudah sync'
                    ]);

                    DB::commit();

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

    // NILAI TRANSFER START
    public function nilai_transfer()
    {
        $semesterAktif = SemesterAktif::first();
        $prodi = ProgramStudi::where('status', 'A')->orderBy('kode_program_studi')->get();
        $semester = Semester::select('nama_semester', 'id_semester')->where('id_semester', '<=', $semesterAktif->id_semester)->orderBy('id_semester', 'desc')->get();

        return view('universitas.feeder-upload.perkuliahan.nilai-transfer', [
            'prodi' => $prodi,
            'semester' => $semester,
            'semesterAktif' => $semesterAktif,
        ]);
    }

    public function nilai_transfer_data(Request $request)
    {

        $prodi = ProgramStudi::where('id',$request->id_prodi)->first();
        // dd($prodi->id_prodi, $request->id_semester);
        // $data = NilaiTransferPendidikan::with('prodi', 'semester', 'aktivitas_mahasiswa')
        //         ->where('nilai_transfer_pendidikans.id_semester', $request->id_semester)
        //         ->where('nilai_transfer_pendidikans.id_prodi', $prodi->id_prodi)
        //         ->where('nilai_transfer_pendidikans.feeder', 0)
        //         ->get();

        $query = NilaiTransferPendidikan::with('prodi', 'semester', 'aktivitas_mahasiswa')
            ->where('nilai_transfer_pendidikans.feeder', 0)
            ->where('nilai_transfer_pendidikans.id_semester', $request->id_semester)
            ->where('nilai_transfer_pendidikans.id_prodi', $prodi->id_prodi);

        // if (isNull(NilaiTransferPendidikan::has('aktivitas_mahasiswa'))) {
        //     $query->doesntHave('aktivitas_mahasiswa');
        //     return response()->json(['error' => 'Data tidak ditemukan'], 404);
        // } else {
        //     $query->whereHas('aktivitas_mahasiswa', function ($subquery) {
        //         $subquery->where('feeder', 1);
        //     });
        //     return response()->json(['error' => 'Data ditemukan'], 404);
        // }


        $data = $query->get();
        // dd($data);

        return response()->json(
            // [
            // 'data' =>
            $data,
            // 'prodi' => $prodi,
        // ]
        );
    }

    public function nilai_transfer_upload(Request $request)
    {
        $prodi = ProgramStudi::find($request->prodi)->id_prodi;
        // $prodi = ProgramStudi::where('id',$request->id_prodi)->first();

        $semester = $request->semester;

        // return response()->json(['message' => $semester.' - '.$prodi]);

        // $data = NilaiTransferPendidikan::with('prodi', 'semester', 'aktivitas_mahasiswa')
        //         // ->where('am.feeder', 1)
        //         ->where('nilai_transfer_pendidikans.feeder', 0)
        //         ->where('nilai_transfer_pendidikans.id_prodi', $prodi)
        //         ->where('nilai_transfer_pendidikans.id_semester', $semester)
        //         ->get();

        $query = NilaiTransferPendidikan::with('prodi', 'semester', 'aktivitas_mahasiswa')
            ->where('nilai_transfer_pendidikans.feeder', 0)
            ->where('nilai_transfer_pendidikans.id_prodi', $prodi)
            ->where('nilai_transfer_pendidikans.id_semester', $semester);

        // if (NilaiTransferPendidikan::has('aktivitas_mahasiswa')->count() > 0) {
        //     $query->whereHas('aktivitas_mahasiswa', function ($subQuery) {
        //         $subQuery->where('feeder', 1);
        //     });
        // }

        $data = $query->get();


        $totalData = $data->count();
        // dd($data);
        if ($totalData == 0) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        $act = 'InsertNilaiTransferPendidikanMahasiswa';
        $actGet = 'GetNilaiTransferPendidikanMahasiswa';
        $dataGagal = 0;
        $dataBerhasil = 0;

        $response = new StreamedResponse(function () use ($data, $totalData, $act, $actGet, &$dataGagal, &$dataBerhasil) {
            foreach ($data as $index => $d) {
                $id_transfer_lama = $d->id_transfer;

                // $judul = $this->convert_ascii($d->judul);
                // $lokasi = $this->convert_ascii($d->lokasi);
                // $keterangan = $this->convert_ascii($d->keterangan);

                // get 100 char only
                // $lokasi = substr($lokasi, 0, 80);

                $record = [
                    "id_transfer" => $d->id_transfer,
                    "id_registrasi_mahasiswa" =>  $d->id_registrasi_mahasiswa,
                    "kode_mata_kuliah_asal" => $d->kode_mata_kuliah_asal,
                    "nama_mata_kuliah_asal" => $d->nama_mata_kuliah_asal,
                    "sks_mata_kuliah_asal" =>  $d->sks_mata_kuliah_asal,
                    "nilai_huruf_asal" => $d->nilai_huruf_asal,
                    "id_matkul" => $d->id_matkul,
                    "sks_mata_kuliah_diakui" => $d->sks_mata_kuliah_diakui,
                    "nilai_huruf_diakui" => $d->nilai_huruf_diakui,
                    "nilai_angka_diakui" => $d->nilai_angka_diakui,
                    "id_perguruan_tinggi" => $d->id_perguruan_tinggi,
                    "id_semester" => $d->id_semester,
                    "id_aktivitas" => $d->id_aktivitas ?? "",
                ];


                $recordGet = "id_transfer = '".$d->id_transfer."'" ;

                $req = new FeederUpload($act, $record, $actGet, $recordGet);
                $result = $req->uploadNilaiTransfer();

                if (isset($result['error_code']) && $result['error_code'] == 0) {

                    DB::beginTransaction();

                    // NilaiTransferPendidikan::where('id_transfer', $id_transfer_lama)->update(['id_transfer' => $result['data']['id_transfer']]);
                    // PrestasiMahasiswa::where('id_aktivitas', $id_transfer_lama)->update(['id_aktivitas' => $result['data']['id_aktivitas']]);
                    // BimbingMahasiswa::where('id_aktivitas', $id_transfer_lama)->update(['id_aktivitas' => $result['data']['id_aktivitas']]);

                    $d->where('id_transfer', $d->id_transfer)->update([
                        'id_transfer' => $result['data']['id_transfer'],
                        'status_sync' => 'sudah_sync',
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
    // NILAI TRANSFER END

    public function aktivitas()
    {
        $semesterAktif = SemesterAktif::first();
        $prodi = ProgramStudi::where('status', 'A')->orderBy('kode_program_studi')->get();
        $semester = Semester::select('nama_semester', 'id_semester')->where('id_semester', '<=', $semesterAktif->id_semester)->orderBy('id_semester', 'desc')->get();

        return view('universitas.feeder-upload.aktivitas.aktivitas-mahasiswa', [
            'prodi' => $prodi,
            'semester' => $semester,
            'semesterAktif' => $semesterAktif,
        ]);
    }

    public function aktivitas_data(Request $request)
    {
        $prodi = ProgramStudi::find($request->id_prodi)->id_prodi;
        $data = AktivitasMahasiswa::where('id_prodi', $prodi)
                ->where('id_semester', $request->id_semester)
                ->where('approve_krs', 1)
                ->where('feeder', 0)
                // ->where('id_aktivitas', 'f2420b62-3e0e-42c0-93c5-3ffb4585dfc1')
                ->get();

        return response()->json($data);
    }

    public function aktivitas_upload(Request $request)
    {
        $prodi = ProgramStudi::find($request->prodi)->id_prodi;

        $semester = $request->semester;

        // return response()->json(['message' => $semester.' - '.$prodi]);

        $data = AktivitasMahasiswa::where('id_prodi', $prodi)
                ->where('id_semester', $semester)
                ->where('approve_krs', 1)
                ->where('feeder', 0)
                // ->where('id_aktivitas', 'f2420b62-3e0e-42c0-93c5-3ffb4585dfc1')
                ->get();

        $totalData = $data->count();
        // dd($data);
        if ($totalData == 0) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        $act = 'InsertAktivitasMahasiswa';
        $actGet = 'GetListAktivitasMahasiswa';
        $dataGagal = 0;
        $dataBerhasil = 0;

        $response = new StreamedResponse(function () use ($data, $totalData, $act, $actGet, &$dataGagal, &$dataBerhasil) {
            foreach ($data as $index => $d) {
                $id_aktivitas_lama = $d->id_aktivitas;

                $judul = $this->convert_ascii($d->judul);
                $lokasi = $this->convert_ascii($d->lokasi);
                $keterangan = $this->convert_ascii($d->keterangan);

                // get 100 char only
                $lokasi = substr($lokasi, 0, 80);

                $record = [
                    'id_aktivitas' => $d->id_aktivitas,
                    // "program_mbkm" => $d->program_mbkm,
                    "judul" => $judul,
                    "id_semester" => $d->id_semester,
                    "id_jenis_aktivitas" => $d->id_jenis_aktivitas,
                    "lokasi" => $lokasi,
                    "sk_tugas" => $d->sk_tugas ?? "",
                    "tanggal_sk_tugas" => $d->tanggal_sk_tugas ?? "",
                    "jenis_anggota" => strval($d->jenis_anggota),
                    "keterangan" => $keterangan,
                    "id_prodi" => $d->id_prodi,
                    "tanggal_mulai" => $d->tanggal_mulai ?? "",
                    "tanggal_selesai" => $d->tanggal_selesai ?? "",
                ];


                $recordGet = "id_aktivitas = '".$d->id_aktivitas."'" ;

                $req = new FeederUpload($act, $record, $actGet, $recordGet);
                $result = $req->uploadAktivitas();

                if (isset($result['error_code']) && $result['error_code'] == 0) {

                    DB::beginTransaction();


                    AktivitasMagang::where('id_aktivitas', $id_aktivitas_lama)->update(['id_aktivitas' => $result['data']['id_aktivitas']]);
                    PrestasiMahasiswa::where('id_aktivitas', $id_aktivitas_lama)->update(['id_aktivitas' => $result['data']['id_aktivitas']]);
                    BimbingMahasiswa::where('id_aktivitas', $id_aktivitas_lama)->update(['id_aktivitas' => $result['data']['id_aktivitas']]);

                    $d->update([
                        'id_aktivitas' => $result['data']['id_aktivitas'],
                        'status_sync' => 'sudah_sync',
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

    public function anggota()
    {
        $semesterAktif = SemesterAktif::first();
        $prodi = ProgramStudi::where('status', 'A')->orderBy('kode_program_studi')->get();
        $semester = Semester::select('nama_semester', 'id_semester')->where('id_semester', '<=', $semesterAktif->id_semester)->orderBy('id_semester', 'desc')->get();

        return view('universitas.feeder-upload.aktivitas.anggota-aktivitas', [
            'prodi' => $prodi,
            'semester' => $semester,
            'semesterAktif' => $semesterAktif,
        ]);
    }

    public function anggota_data(Request $request)
    {
        $prodi = ProgramStudi::find($request->id_prodi)->id_prodi;
        $data = AnggotaAktivitasMahasiswa::join('aktivitas_mahasiswas as a', 'a.id_aktivitas', 'anggota_aktivitas_mahasiswas.id_aktivitas')
                ->join('riwayat_pendidikans as r', 'r.id_registrasi_mahasiswa', 'anggota_aktivitas_mahasiswas.id_registrasi_mahasiswa')
                ->join('program_studis as p', 'r.id_prodi', 'p.id_prodi')
                ->where('a.id_prodi', $prodi)
                ->where('a.id_semester', $request->id_semester)
                ->where('a.approve_krs', 1)
                ->where('a.feeder', 1)
                ->where('anggota_aktivitas_mahasiswas.feeder', 0)
                ->select('anggota_aktivitas_mahasiswas.*', 'a.nama_semester as nama_semester', 'a.nama_prodi as nama_prodi', DB::raw('CONCAT(p.nama_jenjang_pendidikan, " ", p.nama_program_studi) as prodi_mahasiswa'))
                // ->where('id_aktivitas', '4e3b451f-c254-40eb-8b6d-37de00947080')
                ->get();

        return response()->json($data);
    }

    public function anggota_upload(Request $request)
    {
        $prodi = ProgramStudi::find($request->prodi)->id_prodi;

        $data =  AnggotaAktivitasMahasiswa::join('aktivitas_mahasiswas as a', 'a.id_aktivitas', 'anggota_aktivitas_mahasiswas.id_aktivitas')
                                        ->join('riwayat_pendidikans as r', 'r.id_registrasi_mahasiswa', 'anggota_aktivitas_mahasiswas.id_registrasi_mahasiswa')
                                        ->join('program_studis as p', 'r.id_prodi', 'p.id_prodi')
                                        ->where('a.id_prodi', $prodi)
                                        ->where('a.id_semester', $request->semester)
                                        ->where('a.approve_krs', 1)
                                        ->where('a.feeder', 1)
                                        ->where('anggota_aktivitas_mahasiswas.feeder', 0)
                                        ->select('anggota_aktivitas_mahasiswas.*', 'a.nama_semester as nama_semester', 'a.nama_prodi as nama_prodi', DB::raw('CONCAT(p.nama_jenjang_pendidikan, " ", p.nama_program_studi) as prodi_mahasiswa'))
                                        // ->where('id_aktivitas', '4e3b451f-c254-40eb-8b6d-37de00947080')
                                        ->get();

        $totalData = $data->count();
        // dd($data, $totalData, $request->all());
        if ($totalData == 0) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        $act = 'InsertAnggotaAktivitasMahasiswa';
        $actGet = 'GetListAnggotaAktivitasMahasiswa';
        $dataGagal = 0;
        $dataBerhasil = 0;

        $response = new StreamedResponse(function () use ($data, $totalData, $act, $actGet, &$dataGagal, &$dataBerhasil) {
            foreach ($data as $index => $d) {
                $id_anggota_lama = $d->id_anggota;
                $record = [
                    'id_registrasi_mahasiswa' => $d->id_registrasi_mahasiswa,
                    'id_aktivitas' => $d->id_aktivitas,
                    'jenis_peran' => $d->jenis_peran,
                ];


                $recordGet = "id_aktivitas = '".$d->id_aktivitas."'" ;

                $req = new FeederUpload($act, $record, $actGet, $recordGet);
                $result = $req->uploadGeneral();

                if (isset($result['error_code']) && $result['error_code'] == 0) {

                    DB::beginTransaction();

                    KonversiAktivitas::where('id_anggota', $id_anggota_lama)->update(['id_anggota' => $result['data']['id_anggota']]);

                    $d->update([
                        'id_anggota' => $result['data']['id_anggota'],
                        'status_sync' => 'sudah_sync',
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

    public function nilai_konversi()
    {
        $semesterAktif = SemesterAktif::first();
        $prodi = ProgramStudi::where('status', 'A')->orderBy('kode_program_studi')->get();
        $semester = Semester::select('nama_semester', 'id_semester')->where('id_semester', '<=', $semesterAktif->id_semester)->orderBy('id_semester', 'desc')->get();

        return view('universitas.feeder-upload.aktivitas.nilai-konversi', [
            'prodi' => $prodi,
            'semester' => $semester,
            'semesterAktif' => $semesterAktif,
        ]);
    }

    public function nilai_konversi_data(Request $request)
    {
        $prodi = ProgramStudi::find($request->id_prodi)->id_prodi;
        $data = KonversiAktivitas::join('aktivitas_mahasiswas as a', 'a.id_aktivitas', 'konversi_aktivitas.id_aktivitas')
                                ->join('anggota_aktivitas_mahasiswas as aa', 'aa.id_anggota', 'konversi_aktivitas.id_anggota')
                                ->join('mata_kuliahs as m', 'konversi_aktivitas.id_matkul', 'm.id_matkul')
                                ->join('program_studis as p', 'a.id_prodi', 'p.id_prodi')
                                ->where('aa.feeder', 1)
                                ->where('a.feeder', 1)
                                ->where('a.id_prodi', $prodi)
                                ->where('a.id_semester', $request->id_semester)
                                ->where('konversi_aktivitas.feeder', 0)
                                ->select('konversi_aktivitas.*', 'm.kode_mata_kuliah as kode_mata_kuliah', 'm.nama_mata_kuliah as nama_mata_kuliah', DB::raw('CONCAT(p.nama_jenjang_pendidikan, " ", p.nama_program_studi) as prodi'))
                                ->get();


        return response()->json($data);
    }

    public function nilai_konversi_upload(Request $request)
    {
        $prodi = ProgramStudi::find($request->prodi)->id_prodi;

        // $semester = $request->id_semester;

        $data =  KonversiAktivitas::join('aktivitas_mahasiswas as a', 'a.id_aktivitas', 'konversi_aktivitas.id_aktivitas')
                                ->join('anggota_aktivitas_mahasiswas as aa', 'aa.id_anggota', 'konversi_aktivitas.id_anggota')
                                ->where('aa.feeder', 1)
                                ->where('a.feeder', 1)
                                ->where('a.id_prodi', $prodi)
                                ->where('a.id_semester', $request->semester)
                                ->where('konversi_aktivitas.feeder', 0)
                                ->select('konversi_aktivitas.*', 'aa.id_registrasi_mahasiswa as id_registrasi_mahasiswa')
                                ->get();

        $totalData = $data->count();
        // dd($data, $totalData, $request->all());
        if ($totalData == 0) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        $act = 'InsertKonversiKampusMerdeka';
        $actGet = 'GetListKonversiKampusMerdeka';
        $dataGagal = 0;
        $dataBerhasil = 0;

        $response = new StreamedResponse(function () use ($data, $totalData, $act, $actGet, &$dataGagal, &$dataBerhasil) {
            foreach ($data as $index => $d) {

                $record = [
                    "id_semester" => $d->id_semester,
                    "id_matkul" => $d->id_matkul,
                    "id_anggota" => $d->id_anggota,
                    "id_aktivitas" => $d->id_aktivitas,
                    "sks_mata_kuliah" => $d->sks_mata_kuliah,
                    "nilai_angka" => $d->nilai_angka,
                    "nilai_indeks" => $d->nilai_indeks,
                    "nilai_huruf" => $d->nilai_huruf,
                    "id_registrasi_mahasiswa" => $d->id_registrasi_mahasiswa,
                ];


                $recordGet = "id_matkul = '".$d->id_matkul."' AND id_registrasi_mahasiswa = '".$d->id_registrasi_mahasiswa."'" ;

                $req = new FeederUpload($act, $record, $actGet, $recordGet);

                $result = $req->uploadNilaiKonversi();

                if (isset($result['error_code']) && $result['error_code'] == 0) {

                    DB::beginTransaction();

                    $d->update([
                        'id_konversi_aktivitas' => $result['data']['id_konversi_aktivitas'],
                        'status_sync' => 'sudah_sync',
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

    public function pembimbing(Request $request)
    {
        $semesterAktif = SemesterAktif::first();
        $prodi = ProgramStudi::where('status', 'A')->orderBy('kode_program_studi')->get();
        $semester = Semester::select('nama_semester', 'id_semester')->where('id_semester', '<=', $semesterAktif->id_semester)->orderBy('id_semester', 'desc')->get();

        return view('universitas.feeder-upload.aktivitas.pembimbing', [
            'prodi' => $prodi,
            'semester' => $semester,
            'semesterAktif' => $semesterAktif,
        ]);
    }

    public function pembimbing_data(Request $request)
    {
        $prodi = ProgramStudi::find($request->id_prodi)->id_prodi;
        $data = BimbingMahasiswa::join('aktivitas_mahasiswas as a', 'a.id_aktivitas', 'bimbing_mahasiswas.id_aktivitas')
                ->join('biodata_dosens as bd', 'bd.id_dosen', 'bimbing_mahasiswas.id_dosen')
                ->where('a.id_prodi', $prodi)
                ->where('a.id_semester', $request->id_semester)
                ->where('a.approve_krs', 1)
                ->where('a.feeder', 1)
                ->where('bimbing_mahasiswas.feeder', 0)
                ->select('bimbing_mahasiswas.*', 'a.nama_semester as nama_semester', 'a.nama_prodi as nama_prodi')
                // ->where('id_aktivitas', '4e3b451f-c254-40eb-8b6d-37de00947080')
                ->get();

        return response()->json($data);
    }

    public function pembimbing_upload(Request $request)
    {
        $prodi = ProgramStudi::find($request->prodi)->id_prodi;

        $data =  BimbingMahasiswa::join('aktivitas_mahasiswas as a', 'a.id_aktivitas', 'bimbing_mahasiswas.id_aktivitas')
                                ->join('biodata_dosens as bd', 'bd.id_dosen', 'bimbing_mahasiswas.id_dosen')
                                ->where('a.id_prodi', $prodi)
                                ->where('a.id_semester', $request->semester)
                                ->where('a.approve_krs', 1)
                                ->where('a.feeder', 1)
                                ->where('bimbing_mahasiswas.feeder', 0)
                                ->select('bimbing_mahasiswas.*', 'a.nama_semester as nama_semester', 'a.nama_prodi as nama_prodi')
                                // ->where('id_aktivitas', '4e3b451f-c254-40eb-8b6d-37de00947080')
                                ->get();

        $totalData = $data->count();
        // dd($data, $totalData, $request->all());
        if ($totalData == 0) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        $act = 'InsertBimbingMahasiswa';
        $actGet = 'GetListBimbingMahasiswa';
        $dataGagal = 0;
        $dataBerhasil = 0;

        $response = new StreamedResponse(function () use ($data, $totalData, $act, $actGet, &$dataGagal, &$dataBerhasil) {
            foreach ($data as $index => $d) {
                // $id_anggota_lama = $d->id_anggota;
                $record = [
                   "id_aktivitas" => $d->id_aktivitas,
                    "id_kategori_kegiatan" => $d->id_kategori_kegiatan,
                    "id_dosen" => $d->id_dosen,
                    "pembimbing_ke"=> $d->pembimbing_ke,
                ];

                $recordGet = "id_aktivitas = '".$d->id_aktivitas."'" ;

                $req = new FeederUpload($act, $record, $actGet, $recordGet);
                $result = $req->uploadGeneral();

                if (isset($result['error_code']) && $result['error_code'] == 0) {

                    DB::beginTransaction();

                    // KonversiAktivitas::where('id_anggota', $id_anggota_lama)->update(['id_anggota' => $result['data']['id_anggota']]);

                    $d->update([
                        'id_bimbing_mahasiswa' => $result['data']['id_bimbing_mahasiswa'],
                        'status_sync' => 'sudah_sync',
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

    public function penguji()
    {
        $semesterAktif = SemesterAktif::first();
        $prodi = ProgramStudi::where('status', 'A')->orderBy('kode_program_studi')->get();
        $semester = Semester::select('nama_semester', 'id_semester')->where('id_semester', '<=', $semesterAktif->id_semester)->orderBy('id_semester', 'desc')->get();

        return view('universitas.feeder-upload.aktivitas.penguji', [
            'prodi' => $prodi,
            'semester' => $semester,
            'semesterAktif' => $semesterAktif,
        ]);
    }

    public function penguji_data(Request $request)
    {
        $prodi = ProgramStudi::find($request->id_prodi)->id_prodi;
        $data = UjiMahasiswa::join('aktivitas_mahasiswas as a', 'a.id_aktivitas', 'uji_mahasiswas.id_aktivitas')
                ->join('biodata_dosens as bd', 'bd.id_dosen', 'uji_mahasiswas.id_dosen')
                ->where('a.id_prodi', $prodi)
                ->where('a.id_semester', $request->id_semester)
                ->where('a.approve_krs', 1)
                ->where('a.feeder', 1)
                ->where('uji_mahasiswas.status_uji_mahasiswa', 2)
                ->where('uji_mahasiswas.feeder', 0)
                ->select('uji_mahasiswas.*', 'a.nama_semester as nama_semester', 'a.nama_prodi as nama_prodi')
                // ->where('id_aktivitas', '4e3b451f-c254-40eb-8b6d-37de00947080')
                ->get();

        return response()->json($data);
    }

    public function penguji_upload(Request $request)
    {
        $prodi = ProgramStudi::find($request->prodi)->id_prodi;

        $data =   UjiMahasiswa::join('aktivitas_mahasiswas as a', 'a.id_aktivitas', 'uji_mahasiswas.id_aktivitas')
                        ->join('biodata_dosens as bd', 'bd.id_dosen', 'uji_mahasiswas.id_dosen')
                        ->where('a.id_prodi', $prodi)
                        ->where('a.id_semester', $request->semester)
                        ->where('a.approve_krs', 1)
                        ->where('a.feeder', 1)
                        ->where('uji_mahasiswas.status_uji_mahasiswa', 2)
                        ->where('uji_mahasiswas.feeder', 0)
                        ->select('uji_mahasiswas.*', 'a.nama_semester as nama_semester', 'a.nama_prodi as nama_prodi')
                        // ->where('id_aktivitas', '4e3b451f-c254-40eb-8b6d-37de00947080')
                        ->get();

        $totalData = $data->count();
        // dd($data, $totalData, $request->all());
        if ($totalData == 0) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        $act = 'InsertUjiMahasiswa';
        $actGet = 'GetListUjiMahasiswa';
        $dataGagal = 0;
        $dataBerhasil = 0;

        $response = new StreamedResponse(function () use ($data, $totalData, $act, $actGet, &$dataGagal, &$dataBerhasil) {
            foreach ($data as $index => $d) {
                // $id_anggota_lama = $d->id_anggota;
                $record = [
                   "id_aktivitas" => $d->id_aktivitas,
                    "id_kategori_kegiatan" => $d->id_kategori_kegiatan,
                    "id_dosen" => $d->id_dosen,
                    "penguji_ke"=> $d->penguji_ke,
                ];

                $recordGet = "id_uji = '".$d->id_uji."'" ;

                $req = new FeederUpload($act, $record, $actGet, $recordGet);
                $result = $req->uploadGeneral();

                if (isset($result['error_code']) && $result['error_code'] == 0) {

                    DB::beginTransaction();

                    // KonversiAktivitas::where('id_anggota', $id_anggota_lama)->update(['id_anggota' => $result['data']['id_anggota']]);

                    $d->update([
                        'id_uji' => $result['data']['id_uji'],
                        'status_sync' => 'sudah_sync',
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

    private function convert_ascii($string)
    {
      // Replace Single Curly Quotes
      $search[]  = chr(226).chr(128).chr(152);
      $replace[] = "'";
      $search[]  = chr(226).chr(128).chr(153);
      $replace[] = "'";
      // Replace Smart Double Curly Quotes
      $search[]  = chr(226).chr(128).chr(156);
      $replace[] = '"';
      $search[]  = chr(226).chr(128).chr(157);
      $replace[] = '"';
      // Replace En Dash
      $search[]  = chr(226).chr(128).chr(147);
      $replace[] = '--';
      // Replace Em Dash
      $search[]  = chr(226).chr(128).chr(148);
      $replace[] = '---';
      // Replace Bullet
      $search[]  = chr(226).chr(128).chr(162);
      $replace[] = '*';
      // Replace Middle Dot
      $search[]  = chr(194).chr(183);
      $replace[] = '*';
      // Replace Ellipsis with three consecutive dots
      $search[]  = chr(226).chr(128).chr(166);
      $replace[] = '...';
      // Apply Replacements
      $string = str_replace($search, $replace, $string);
      // Remove any non-ASCII Characters
      $string = preg_replace("/[^\x01-\x7F]/","", $string);

    //   $string = preg_replace("/[().,-]/", " ", $string);

      $string = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $string);

      $string = preg_replace( '/[^[:print:]]/', '',$string);
      $string = trim($string);
      return $string;
    }
}
