<?php

namespace App\Http\Controllers\Mahasiswa\Bimbingan;

use Illuminate\Http\Request;
use App\Models\SemesterAktif;
use App\Models\AsistensiAkhir;
use App\Http\Controllers\Controller;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Perkuliahan\BimbingMahasiswa;
use App\Models\Perkuliahan\AktivitasMahasiswa;
use App\Models\Perkuliahan\PesertaKelasKuliah;

class BimbinganController extends Controller
{
    public function bimbingan_tugas_akhir1(Request $request)
    {
        return view('mahasiswa.bimbingan.tugas-akhir.index', [
            // 'data' => $data,
            // 'semester' => $semester,
            // 'id_semester' => $id_semester,
        ]);
    }

    public function bimbingan_tugas_akhir2(RiwayatPendidikan $riwayat)
    {
        $id = $riwayat->id_registrasi_mahasiswa;
        $semester = SemesterAktif::first()->id_semester;
        $data = PesertaKelasKuliah::with(['kelas_kuliah', 'kelas_kuliah.matkul'])
                ->whereHas('kelas_kuliah', function($query) use ($semester) {
                    $query->where('id_semester', $semester);
                })
                ->where('id_registrasi_mahasiswa', $id)
                ->orderBy('kode_mata_kuliah')
                ->get();

        // dd($data);

        return view('mahasiswa.bimbingan.tugas-akhir.index', [
            'riwayat' => $riwayat,
            'data' => $data,
        ]);
    }

    public function bimbingan_tugas_akhir(AktivitasMahasiswa $aktivitas)
    {
        $data = AsistensiAkhir::where('id_aktivitas', $aktivitas->id_aktivitas)->get();

        $aktivitas = $aktivitas->load(['bimbing_mahasiswa', 'anggota_aktivitas_personal', 'prodi']);

        $pembimbing_ke = BimbingMahasiswa::where('id_aktivitas', $aktivitas->id_aktivitas)
                            // ->where('id_aktivitas', auth()->user()->fk_id)
                            ->first()
                            // ->pembimbing_ke
                            ;
        dd($aktivitas);
        return view('mahasiswa.bimbingan.tugas-akhir.index', [
            'data' => $data,
            'aktivitas' => $aktivitas,
            'pembimbing_ke' => $pembimbing_ke,
        ]);
    }
}
