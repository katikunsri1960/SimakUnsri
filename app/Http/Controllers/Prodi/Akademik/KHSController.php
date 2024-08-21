<?php

namespace App\Http\Controllers\Prodi\Akademik;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Perkuliahan\AktivitasKuliahMahasiswa;
use App\Models\Perkuliahan\AktivitasMahasiswa;
use App\Models\Perkuliahan\KonversiAktivitas;
use App\Models\Perkuliahan\NilaiPerkuliahan;
use App\Models\Perkuliahan\NilaiTransferPendidikan;
use App\Models\Perkuliahan\PesertaKelasKuliah;
use App\Models\Semester;
use App\Models\SemesterAktif;
use Illuminate\Http\Request;

class KHSController extends Controller
{
    public function khs()
    {
        $semesters = Semester::orderBy('id_semester', 'desc')->get();
        $semesterAktif = SemesterAktif::with('semester')->first();
        return view('prodi.data-akademik.khs.index',[
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

        $nilai = NilaiPerkuliahan::where('id_registrasi_mahasiswa', $riwayat->id_registrasi_mahasiswa)
                // ->where('id_semester', $semester)
                ->orderBy('id_semester')
                ->get();

        $konversi = KonversiAktivitas::with(['matkul'])->join('anggota_aktivitas_mahasiswas as ang', 'konversi_aktivitas.id_anggota', 'ang.id_anggota')
                    // ->where('id_semester', $semester)
                    ->where('ang.id_registrasi_mahasiswa', $riwayat->id_registrasi_mahasiswa)
                    ->get();

        $transfer = NilaiTransferPendidikan::where('id_registrasi_mahasiswa', $riwayat->id_registrasi_mahasiswa)
                    ->get();

        $akm = AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa', $riwayat->id_registrasi_mahasiswa)
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
}
