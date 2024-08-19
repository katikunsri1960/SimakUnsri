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
                ->get();

        if($krs->isEmpty() && $aktivitas->isEmpty()) {
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
            'riwayat' => $riwayat,
        ];


        return response()->json($response);
    }

    public function approve(Request $request)
    {
        $nim = $request->nim;
        $semester = $request->semester;

        $riwayat = RiwayatPendidikan::where('nim', $nim)->first();

        if(!$riwayat) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data mahasiswa tidak ditemukan',
            ]);
        }

        $krs = PesertaKelasKuliah::where('id_registrasi_mahasiswa', $riwayat->id_registrasi_mahasiswa)
                ->whereHas('kelas_kuliah', function($query) use ($semester) {
                    $query->where('id_semester', $semester);
                })
                ->where('approved', '0')
                ->get();

        $aktivitas = AktivitasMahasiswa::with('anggota_aktivitas_personal', 'konversi')
                ->whereHas('anggota_aktivitas_personal', function($query) use ($riwayat) {
                    $query->where('id_registrasi_mahasiswa', $riwayat->id_registrasi_mahasiswa);
                })
                ->where('id_semester', $semester)
                ->get();

        if($krs->isEmpty() && $aktivitas->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data KRS tidak ditemukan',
            ]);
        }

        DB::beginTransaction();
        
        foreach($krs as $item) {
            $item->approved = 1;
            $item->save();
        }

        foreach($aktivitas as $ak) {
            $ak->update([
                'approve_krs' => 1,
                'tanggal_approve' => now(),
            ]);
        }

        DB::commit();


        $response = [
            'status' => 'success',
            'message' => 'KRS berhasil diapprove',
        ];

        return response()->json($response);
    }
}
