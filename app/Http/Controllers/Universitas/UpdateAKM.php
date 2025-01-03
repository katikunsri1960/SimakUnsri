<?php

namespace App\Http\Controllers\Universitas;

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

class UpdateAKM extends Controller
{
    public function akm(Request $request)
    {
        $semesters = Semester::orderBy('id_semester', 'desc')->get();
        $semesterAktif = SemesterAktif::with('semester')->first();
        
        return view('universitas.monitoring.update-akm.index', [
            // return view('fakultas.data-akademik.khs.index',[
            'semesters' => $semesters,
            'semesterAktif' => $semesterAktif,
        ]);
    }

    public function data(Request $request)
    {
        $semester = $request->semester;
        
        $akm = AktivitasKuliahMahasiswa::with('riwayat_pendidikan', 'prodi')
                    ->where('id_semester', $semester)
                    ->get();

        if($akm->isEmpty() ) {
            $response = [
                'status' => 'error',
                'message' => 'Data AKM pada Semester yang dipilih tidak ditemukan!',
            ];
            return response()->json($response);
        }

        $response = [
            'status' => 'success',
            'message' => 'Data KRS berhasil diambil',
            'akm' => $akm,
        ];

        return response()->json($response);
    }

    public function hitungIps(Request $request)
    {
        try {
            $semester = $request->input('semester');

            if (!$semester) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tahun akademik harus diisi.'
                ], 400);
            }

            // Ambil data AKM mahasiswa berdasarkan semester
            $akmData = AktivitasKuliahMahasiswa::where('id_semester', $semester)->get();

            if($akmData->isEmpty() ) {
                $response = [
                    'status' => 'error',
                    'message' => 'Data AKM pada Semester yang dipilih tidak ditemukan!',
                ];
                return response()->json($response);
            }

            foreach ($akmData as $akm) {
                // Contoh perhitungan IPS (sesuaikan logika dengan kebutuhan Anda)
                $khs = NilaiPerkuliahan::where('id_registrasi_mahasiswa', $akm->id_registrasi_mahasiswa)
                    ->where('id_semester', $request->semester)
                    ->orderBy('id_semester')
                    ->get();

                $khs_konversi = KonversiAktivitas::with(['matkul'])->join('anggota_aktivitas_mahasiswas as ang', 'konversi_aktivitas.id_anggota', 'ang.id_anggota')
                            ->where('id_semester', $request->semester)
                            ->where('ang.id_registrasi_mahasiswa', $akm->id_registrasi_mahasiswa)
                            ->get();

                $khs_transfer = NilaiTransferPendidikan::where('id_registrasi_mahasiswa', $akm->id_registrasi_mahasiswa)
                            ->where('id_semester', $request->semester)
                            ->get();

                $total_sks_semester = $khs->sum('sks_mata_kuliah') + $khs_transfer->sum('sks_mata_kuliah_diakui') + $khs_konversi->sum('sks_mata_kuliah');
                $bobot = 0; $bobot_transfer= 0; $bobot_konversi= 0;

                
                // dd($semester, $tahun_ajaran, $prodi);
                foreach ($khs as $t) {
                    $bobot += $t->nilai_indeks * $t->sks_mata_kuliah;
                }

                foreach ($khs_transfer as $tf) {
                    $bobot_transfer += $tf->nilai_angka_diakui * $tf->sks_mata_kuliah_diakui;
                }

                foreach ($khs_konversi as $kv) {
                    $bobot_konversi += $kv->nilai_indeks * $kv->sks_mata_kuliah;
                }

                $total_bobot= $bobot + $bobot_transfer + $bobot_konversi;
                
                $ips = 0;
                if($total_sks_semester > 0){
                    $ips = $total_bobot / $total_sks_semester;
                }

                // Update nilai IPS pada tabel
                $akm->update([
                    'feeder'=>0,
                    'ips' => round($ips, 2) // Simpan dengan pembulatan 2 desimal
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Nilai IPS berhasil dihitung dan diperbarui.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
