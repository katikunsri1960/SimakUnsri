<?php

namespace App\Http\Controllers\Fakultas\Akademik;

use Ramsey\Uuid\Uuid;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use App\Models\SemesterAktif;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Dosen\PenugasanDosen;
use App\Models\Referensi\KategoriKegiatan;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Perkuliahan\BimbingMahasiswa;
use App\Models\Perkuliahan\AktivitasMahasiswa;
use App\Models\Semester;

class AktivitasNonTAController extends Controller
{
    public function index(Request $request)
    {
        $prodi_fak = ProgramStudi::where('fakultas_id', auth()->user()->fk_id)
                    ->orderBy('id_jenjang_pendidikan')
                    ->orderBy('nama_program_studi')
                    ->get();
        
        $id_prodi_fak=$prodi_fak->pluck('id_prodi');

        $angkatan = RiwayatPendidikan::with(['prodi'])
                    ->whereIn('id_prodi', $id_prodi_fak)
                    ->select(DB::raw('LEFT(id_periode_masuk, 4) as angkatan_raw'))
                    ->distinct()
                    ->orderBy('angkatan_raw', 'desc')
                    ->get();

        $semesterAktif = SemesterAktif::first();
        $daftar_semester = Semester::orderBy('id_semester', 'DESC')
                            ->whereBetween('id_semester', ['20201', $semesterAktif->id_semester])
                            ->get();
        $data = AktivitasMahasiswa::with(['bimbing_mahasiswa', 'anggota_aktivitas_personal', 'prodi'])
                    ->withCount([
                        'bimbing_mahasiswa as approved' => function($query) {
                            $query->where('approved', 0);
                        },
                        'bimbing_mahasiswa as approved_dosen' => function($query) {
                            $query->where('approved_dosen', 0);
                        },
                        'bimbing_mahasiswa as decline_dosen' => function($query) {
                            $query->where('approved_dosen', 2);
                        },
                    ])
                    ->where('id_semester', $semesterAktif->id_semester)
                    ->whereIn('id_jenis_aktivitas', [5,6,13,14,15,16,17,18,19,20,21])
                    // ->get()
                    ;
        // dd($request->daftar_semester);
        
        if ($request->has('prodi') && !empty($request->prodi)) {
            $filter = $request->prodi;
            $data->whereIn('id_prodi', $filter);
        }else{
            $data = $data->whereIn('id_prodi', $id_prodi_fak);
        }

        $data=$data->get();

        return view('fakultas.data-akademik.non-tugas-akhir.index', [
            'data' => $data,
            'prodi' => $prodi_fak,
            'daftar_semester' => $daftar_semester,
        ]);
    } 
}
