<?php

namespace App\Http\Controllers\Prodi\Akademik;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Perkuliahan\AktivitasMahasiswa;
use App\Models\Perkuliahan\PesertaKelasKuliah;
use App\Models\Semester;
use App\Models\SemesterAktif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KRSController extends Controller
{
    public function krs()
    {
        $semesters = Semester::orderBy('id_semester', 'desc')->get();
        $semesterAktif = SemesterAktif::with('semester')->first();
        return view('prodi.data-akademik.krs.index', [
            'semesters' => $semesters,
            'semesterAktif' => $semesterAktif,
        ]);
    }

    public function data(Request $request)
    {
        $semester = $request->semester;
        $nim = $request->nim;

        $riwayat = RiwayatPendidikan::with('dosen_pa', 'prodi.jurusan', 'prodi.fakultas')->where('nim', $nim)->first();
        // dd($riwayat);
        if (!$riwayat || $riwayat->id_prodi != auth()->user()->fk_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data mahasiswa tidak ditemukan',
            ]);
        }

        $krs = PesertaKelasKuliah::with('kelas_kuliah.matkul')->where('id_registrasi_mahasiswa', $riwayat->id_registrasi_mahasiswa)
                ->whereHas('kelas_kuliah' , function($query) use ($semester) {
                    $query->where('id_semester', $semester);
                })
                ->get();

        $aktivitas = AktivitasMahasiswa::with('anggota_aktivitas_personal', 'konversi')
                ->whereHas('anggota_aktivitas_personal', function($query) use ($riwayat) {
                    $query->where('id_registrasi_mahasiswa', $riwayat->id_registrasi_mahasiswa);
                })
                ->where('id_semester', $semester)
                ->whereIn('id_jenis_aktivitas', [1,2,3,4,5,6,22])
                ->get();

        $aktivitas_mbkm = AktivitasMahasiswa::with('anggota_aktivitas_personal', 'konversi')
                    ->whereHas('anggota_aktivitas_personal', function($query) use ($riwayat) {
                        $query->where('id_registrasi_mahasiswa', $riwayat->id_registrasi_mahasiswa);
                    })
                    ->where('id_semester', $semester)
                    ->whereIn('id_jenis_aktivitas',[13,14,15,16,17,18,19,20,21])
                    ->get();

        if($krs->isEmpty() && $aktivitas->isEmpty() && $aktivitas_mbkm->isEmpty()) {
            $response = [
                'status' => 'error',
                'message' => 'Data KRS tidak ditemukan!',
            ];
            return response()->json($response);
        }

        $response = [
            'status' => 'success',
            'message' => 'Data KRS berhasil diambil',
            'krs' => $krs,
            'aktivitas' => $aktivitas,
            'aktivitas_mbkm' => $aktivitas_mbkm,
            'riwayat' => $riwayat,
        ];


        return response()->json($response);
    }

    public function approve(Request $request)
    {
        $nim = $request->nim;
        $semester = $request->semester;

        $riwayat = RiwayatPendidikan::where('nim', $nim)->orderBy('id_periode_masuk', 'desc')->first();

        if(!$riwayat) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data mahasiswa tidak ditemukan',
            ]);
        }

        $db = new PesertaKelasKuliah();

        $response = $db->approve_all($riwayat->id_registrasi_mahasiswa);

        return response()->json($response);
    }
}
