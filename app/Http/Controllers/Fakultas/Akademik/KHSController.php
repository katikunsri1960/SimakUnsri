<?php

namespace App\Http\Controllers\Fakultas\Akademik;

use Carbon\Carbon;
use App\Models\Semester;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use App\Models\SemesterAktif;
use App\Models\PejabatFakultas;
use App\Models\Perpus\BebasPustaka;
use App\Http\Controllers\Controller;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Perkuliahan\NilaiPerkuliahan;
use App\Models\Perkuliahan\KonversiAktivitas;
use App\Models\Perkuliahan\AktivitasMahasiswa;
use App\Models\Perkuliahan\PesertaKelasKuliah;
use App\Models\Perkuliahan\TranskripMahasiswa;
use App\Models\Perkuliahan\NilaiTransferPendidikan;
use App\Models\Perkuliahan\AktivitasKuliahMahasiswa;
use Illuminate\Support\Facades\DB;

class KHSController extends Controller
{
    public function khs(Request $request)
    {
        $semesters = Semester::orderBy('id_semester', 'desc')->get();
        $semesterAktif = SemesterAktif::with('semester')->first();
        return view('fakultas.data-akademik.khs.index',[
            'semesters' => $semesters,
            'semesterAktif' => $semesterAktif,
        ]);
    }

    public function data(Request $request)
    {
        $id_prodi_fak = ProgramStudi::where('fakultas_id', auth()->user()->fk_id)
                    ->pluck('id_prodi');

        $semester = $request->semester;
        $nim = $request->nim;

        $riwayat = RiwayatPendidikan::with('dosen_pa', 'prodi.jurusan', 'prodi.fakultas')
                    ->where('nim', $nim)
                    ->whereIn('id_prodi', $id_prodi_fak)
                    ->first();

        if (!$riwayat) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data mahasiswa tidak ditemukan',
            ]);
        }

        $nilai = NilaiPerkuliahan::where('id_registrasi_mahasiswa', $riwayat->id_registrasi_mahasiswa)
                ->where('id_semester', $semester)
                ->orderBy('id_semester')
                ->get();

        $konversi = KonversiAktivitas::with(['matkul'])->join('anggota_aktivitas_mahasiswas as ang', 'konversi_aktivitas.id_anggota', 'ang.id_anggota')
                    ->where('id_semester', $semester)
                    ->where('ang.id_registrasi_mahasiswa', $riwayat->id_registrasi_mahasiswa)
                    ->get();

        $transfer = NilaiTransferPendidikan::where('id_registrasi_mahasiswa', $riwayat->id_registrasi_mahasiswa)
                    ->where('id_semester', $semester)
                    ->get();

        $akm = AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa', $riwayat->id_registrasi_mahasiswa)
                ->where('id_semester', $semester)
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

    public function download(Request $request)
    {
        // dd($request->id_semester);

        $request->validate([
            'nim' => 'required',
        ]);

        $riwayat = RiwayatPendidikan::with(['prodi.fakultas', 'prodi.jurusan', 'pembimbing_akademik'])->where('nim', $request->nim)->orderBy('id_periode_masuk', 'desc')->first();

        if(!$riwayat) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data Mahasiswa tidak ditemukan!!',
            ]);
        }

        $khs = NilaiPerkuliahan::where('id_registrasi_mahasiswa', $riwayat->id_registrasi_mahasiswa)
                    ->where('id_semester', $request->id_semester)
                    ->get();

        $total_sks = $khs->sum('sks_mata_kuliah');
        $bobot = 0;

        $semester = Semester::where('id_semester', $request->id_semester)->first();

        foreach ($khs as $t) {
            $bobot += $t->nilai_indeks * $t->sks_mata_kuliah;
        }

        //  dd($semester);
        $ipk = number_format($bobot / $total_sks, 2);

        $akm = AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa', $riwayat->id_registrasi_mahasiswa)
                ->where('id_semester', $request->id_semester)
                ->first();

        $pdf = PDF::loadview('fakultas.data-akademik.khs.pdf', [
            'khs' => $khs,
            'riwayat' => $riwayat,
            'semester' => $semester,
            'akm' => $akm,
            'total_sks' => $total_sks,
            'ipk' => $ipk,
            'today'=> Carbon::now(),
            'wd1' => PejabatFakultas::where('id_fakultas', $riwayat->prodi->fakultas_id)->where('id_jabatan', 1)->first(),
            'bebas_pustaka' => BebasPustaka::where('id_registrasi_mahasiswa', $riwayat->id_registrasi_mahasiswa)->first(),
         ])
         ->setPaper('a4', 'portrait');
        //  dd($pdf);

         return $pdf->stream('KHS-'.$riwayat->nim.'-'.$semester->nama_semester.'.pdf');
    }

    public function khs_angkatan()
    {
        $semesters = Semester::orderBy('id_semester', 'desc')->get();
        $semesterAktif = SemesterAktif::with('semester')->first();
        $prodi = ProgramStudi::where('fakultas_id', auth()->user()->fk_id)->orderBy('kode_program_studi')->get();

        $arrayProdi = $prodi->pluck('id_prodi');

        $angkatan = RiwayatPendidikan::whereIn('id_prodi', $arrayProdi)
                    ->select(DB::raw('LEFT(id_periode_masuk, 4) as angkatan_raw'))
                    ->distinct()
                    ->orderBy('angkatan_raw', 'desc')
                    ->get();

        return view('fakultas.data-akademik.khs.angkatan.index',[
            'semesters' => $semesters,
            'semesterAktif' => $semesterAktif,
            'angkatan' => $angkatan,
            'prodi' => $prodi,
        ]);
    }

    public function khs_angkatan_data(Request $request)
    {
        if($request->semester=='' || $request->angkatan=='' || $request->prodi=='') {
            return response()->json([
                'status' => 'error',
                'message' => 'Semester dan Angkatan harus diisi!',
            ]);
        }

        $checkProdi = ProgramStudi::where('fakultas_id', auth()->user()->fk_id)->where('id_prodi', $request->prodi)->first();

        if(!$checkProdi) {
            return response()->json([
                'status' => 'error',
                'message' => 'Prodi tidak ditemukan!',
            ]);
        }

        $riwayat = RiwayatPendidikan::with(['prodi.fakultas', 'prodi.jurusan', 'pembimbing_akademik'])
                    ->where('id_prodi', $request->prodi)
                    ->where(DB::raw('LEFT(id_periode_masuk, 4)'), $request->angkatan)
                    ->get();

        if ($riwayat->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data Mahasiswa Prodi dan angkatan ini tidak ditemukan!',
            ]);
        }

        $data = [];

        foreach($riwayat as $d){
            $akm = AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa', $d->id_registrasi_mahasiswa)
                    ->where('id_semester', $request->semester)
                    ->first();

            $nilai = NilaiPerkuliahan::where('id_registrasi_mahasiswa', $riwayat->id_registrasi_mahasiswa)
                    ->where('id_semester', $request->semester)
                    ->orderBy('id_semester')
                    ->get();

            $konversi = KonversiAktivitas::with(['matkul'])->join('anggota_aktivitas_mahasiswas as ang', 'konversi_aktivitas.id_anggota', 'ang.id_anggota')
                        ->where('id_semester', $request->semester)
                        ->where('ang.id_registrasi_mahasiswa', $riwayat->id_registrasi_mahasiswa)
                        ->get();

            $transfer = NilaiTransferPendidikan::where('id_registrasi_mahasiswa', $riwayat->id_registrasi_mahasiswa)
                        ->where('id_semester', $request->semester)
                        ->get();

            $data[] = [
                'riwayat' => $d,
                'akm' => $akm,
                'nilai' => $nilai,
                'konversi' => $konversi,
                'transfer' => $transfer,
            ];
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data KHS berhasil diambil',
            'data' => $data,
        ]);
    }
}
