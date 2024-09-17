<?php

namespace App\Http\Controllers\Fakultas\Akademik;

use Ramsey\Uuid\Uuid;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use App\Models\SemesterAktif;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Dosen\PenugasanDosen;
use App\Models\Perkuliahan\UjiMahasiswa;
use App\Models\Referensi\KategoriKegiatan;
use App\Models\Perkuliahan\BimbingMahasiswa;
use App\Models\Perkuliahan\AktivitasMahasiswa;

class SidangMahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $prodi_fak = ProgramStudi::where('fakultas_id', auth()->user()->fk_id)
        ->orderBy('id_jenjang_pendidikan')
        ->orderBy('nama_program_studi')
        ->get();

        $id_prodi_fak=$prodi_fak->pluck('id_prodi');

        $semesterAktif = SemesterAktif::first();
        $semester = $semesterAktif->id_semester;
        
        $data = AktivitasMahasiswa::with(['uji_mahasiswa', 'bimbing_mahasiswa','anggota_aktivitas_personal', 'prodi'])
                    ->withCount([
                        'uji_mahasiswa as status_uji' => function($query) {
                            $query->where('status_uji_mahasiswa', 0);
                        },
                        'uji_mahasiswa as approved_prodi' => function($query) {
                            $query->where('status_uji_mahasiswa', 1);
                        },
                        'uji_mahasiswa as decline_dosen' => function($query) {
                            $query->where('status_uji_mahasiswa', 3);
                        },
                    ])
                    // ->whereIn('id_prodi', $id_prodi_fak)
                    ->where('approve_sidang', 1)
                    ->where('id_semester', $semester)
                    ->whereIn('id_jenis_aktivitas', [1,2,3,4,22]);

        if ($request->has('prodi') && !empty($request->prodi)) {
            $filter = $request->prodi;
            $data->whereIn('id_prodi', $filter);
        }else{
            $data = $data->whereIn('id_prodi', $id_prodi_fak);
        }

        $data=$data->get();
        // dd($data);
        return view('fakultas.data-akademik.sidang-mahasiswa.index', [
            'data' => $data,
            'prodi' => $prodi_fak,
            'semester' => $semester,
        ]);
    }

    public function detail_sidang($aktivitas)
    {
        $id_dosen = auth()->user()->fk_id;
        $data = AktivitasMahasiswa::with(['bimbing_mahasiswa', 'anggota_aktivitas_personal', 'anggota_aktivitas_personal.mahasiswa', 'konversi', 'uji_mahasiswa'])->where('id', $aktivitas)->first();
        $data_pelaksanaan_sidang = AktivitasMahasiswa::with(['revisi_sidang', 'notulensi_sidang', 'penilaian_sidang', 'revisi_sidang.dosen', 'penilaian_sidang.dosen'])->where('id', $aktivitas)->first();
        $penguji = UjiMahasiswa::where('id_aktivitas', $data->id_aktivitas)->where('id_dosen', $id_dosen)->first();
        // dd($data_pelaksanaan_sidang);
        return view('fakultas.data-akademik.sidang-mahasiswa.detail', [
            'data' => $data,
            'data_pelaksanaan' => $data_pelaksanaan_sidang,
            'penguji' => $penguji
        ]);
    }
}
