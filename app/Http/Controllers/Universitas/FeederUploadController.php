<?php

namespace App\Http\Controllers\Universitas;

use App\Http\Controllers\Controller;
use App\Models\Perkuliahan\AktivitasKuliahMahasiswa;
use App\Models\ProgramStudi;
use App\Models\Semester;
use App\Models\SemesterAktif;
use App\Services\Feeder\FeederUpload;
use Illuminate\Http\Request;
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
        $data = AktivitasKuliahMahasiswa::leftJoin('pembiayaans', 'aktivitas_kuliah_mahasiswas.id_pembiayaan', 'pembiayaans.id_pembiayaan')->where('feeder', 0)
            ->where('id_semester', $request->id_semester)
            ->where('id_prodi', $request->id_prodi)
            ->get();

        return response()->json($data);
    }

    public function upload_akm(Request $request)
    {

        $data = AktivitasKuliahMahasiswa::where('feeder', 0)
            ->where('id_semester', '20241')
            ->where('id_prodi', 'b68efc34-c0f0-4334-9970-e02d769e3f49')
            ->whereNotNull('id_pembiayaan')
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
                    // $d->update(['feeder' => 1]);
                    $dataBerhasil++;
                } else {
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

    public function upload_akm_ajax(Request $request)
    {
        // Start the upload process and return a response immediately
        // The actual progress updates will be handled by the uploadAkmProgress method
        return response()->json(['message' => 'Upload started']);
    }

    public function kelas()
    {
        return view('universitas.feeder-upload.kelas.index');
    }

    public function data(Request $request)
    {

    }
}
