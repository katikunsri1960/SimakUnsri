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
use App\Models\Perkuliahan\BimbingMahasiswa;
use App\Models\Perkuliahan\AktivitasMahasiswa;

class TugasAkhirController extends Controller
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
        $data = AktivitasMahasiswa::with(['bimbing_mahasiswa', 'anggota_aktivitas_personal', 'prodi', 'konversi' ])
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
        return view('fakultas.data-akademik.tugas-akhir.index', [
            'data' => $data,
            'prodi' => $prodi_fak,
            'semester' => $semester,
        ]);
    }

}
