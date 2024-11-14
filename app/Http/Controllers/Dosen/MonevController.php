<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Dosen\PenugasanDosen;
use App\Models\Perkuliahan\AktivitasMahasiswa;
use App\Models\Perkuliahan\AnggotaAktivitasMahasiswa;
use App\Models\Perkuliahan\BimbingMahasiswa;
use App\Models\Perkuliahan\DosenPengajarKelasKuliah;
use App\Models\ProgramStudi;
use App\Models\SemesterAktif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MonevController extends Controller
{
    public function pa_prodi()
    {

        $id_dosen = auth()->user()->fk_id;
        $semester = SemesterAktif::join('semesters', 'semester_aktifs.id_semester', 'semesters.id_semester')
                                ->select('semesters.id_tahun_ajaran as id_tahun_ajaran', 'semester_aktifs.id_semester as id_semester')->first();

        $id_registrasi_dosen = PenugasanDosen::where('id_dosen', $id_dosen)
                                ->where('id_tahun_ajaran', $semester->id_tahun_ajaran)
                                ->first()->id_registrasi_dosen;

        $id_prodi_penugasan = DosenPengajarKelasKuliah::where('id_registrasi_dosen', $id_registrasi_dosen)
                                ->where('id_semester', $semester->id_semester)
                                ->distinct('id_prodi')->pluck('id_prodi');

        $prodi = ProgramStudi::whereIn('id_prodi', $id_prodi_penugasan)->get();

        return view('dosen.monev.pa-prodi', [
            'prodi' => $prodi,
            'semester' => $semester
        ]);
    }

    public function pa_prodi_get_monev(Request $request)
    {
        $id_prodi = $request->id_prodi;
        $semester = SemesterAktif::first()->id_semester;
        $db = new BimbingMahasiswa();

        $aktivitas = $db->aktivitas_pa_prodi($id_prodi, $semester);

        return response()->json([
            'status' => $aktivitas->isEmpty() ? 0 : 1,
            'message' => $aktivitas->isEmpty() ? 'Data tidak ditemukan' : 'Data berhasil diambil',
            'data' => $aktivitas->isEmpty() ? [] : $aktivitas
        ]);
    }

    public function pa_prodi_get_anggota_monev(Request $request)
    {
        $aktivitas = AktivitasMahasiswa::where('id',$request->id)->select('id_aktivitas')->first();
        $bimbingan = BimbingMahasiswa::where('id_aktivitas', $aktivitas->id_aktivitas)->first();

        $data = AnggotaAktivitasMahasiswa::where('id_aktivitas', $aktivitas->id_aktivitas)
                                        ->join('riwayat_pendidikans as r', 'anggota_aktivitas_mahasiswas.id_registrasi_mahasiswa', 'r.id_registrasi_mahasiswa')
                                        ->select('r.nim as nim', 'r.nama_mahasiswa as nama_mahasiswa', DB::raw('LEFT(id_periode_masuk, 4) as angkatan'))->get();

        return response()->json([
            'status' => $data->isEmpty() ? 0 : 1,
            'message' => $data->isEmpty() ? 'Data tidak ditemukan' : 'Data berhasil diambil',
            'data' => $data->isEmpty() ? [] : $data,
            'bimbingan' => $bimbingan
        ]);
    }
}
