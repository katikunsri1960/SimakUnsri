<?php

namespace App\Http\Controllers\DPPM;

use App\Models\Semester;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use App\Models\SemesterAktif;
use App\Models\KuisonerAnswer;
use Illuminate\Support\Facades\DB;
use App\Models\Perpus\BebasPustaka;
use App\Http\Controllers\Controller;
use App\Models\JenisBeasiswaMahasiswa;
use App\Models\Perkuliahan\MataKuliah;
use App\Models\Perkuliahan\KelasKuliah;
use App\Models\Perkuliahan\ListKurikulum;

class KuisionerController extends Controller
{
    // public function index()
    // {
    //     return view('dppm.kuisioner.index');
    // }

    public function index(Request $request)
    {
        $prodi = ProgramStudi::with('fakultas')
                    ->where('status', 'A')
                    ->orderBy('fakultas_id', 'ASC')
                    ->orderBy('id_jenjang_pendidikan', 'ASC')
                    ->orderBy('nama_program_studi', 'ASC')
                    ->get();
        // $jenisBeasiswa = JenisBeasiswaMahasiswa::all();
        return view('dppm.kuisioner.index', [
            'prodi' => $prodi,
            // 'jenisBeasiswa' => $jenisBeasiswa,
        ]);
    }

    public function data(Request $request)
    {
        $searchValue = $request->input('search.value');

        $semesterAktif = SemesterAktif::first();

        $query = KuisonerAnswer::with('kelas_kuliah', 'riwayat_pendidikan');

                // dd($query);

        if ($searchValue) {
            $query = $query->where('beasiswa_mahasiswas.nim', 'like', '%' . $searchValue . '%')
                ->orWhere('beasiswa_mahasiswas.nama_mahasiswa', 'like', '%' . $searchValue . '%');
        }

        if ($request->has('prodi') && !empty($request->prodi)) {
            $filter = $request->prodi;
            $query->whereIn('id_prodi', $filter);
        }

        if ($request->has('jenis_beasiswa') && !empty($request->jenis_beasiswa)) {
            $beasiswa = $request->jenis_beasiswa;
            $query->whereIn('id_jenis_beasiswa', $beasiswa);
        }

        $recordsFiltered = $query->count();

        $limit = $request->input('length');
        $offset = $request->input('start');

        // Define the column names that correspond to the DataTables column indices
        if ($request->has('order')) {
            $orderColumn = $request->input('order.0.column');
            $orderDirection = $request->input('order.0.dir');

            // Define the column names that correspond to the DataTables column indices
            $columns = ['nama_program_studi', 'nim', 'nama_mahasiswa', 'id_periode_masuk', 'tanggal_mulai_beasiswa', 'tanggal_akhir_beasiswa'];

            // if ($columns[$orderColumn] == 'prodi') {
            //     $query = $query->join('program_studis as prodi', 'mata_kuliahs.id_prodi', '=', 'prodi.id')
            //         ->orderBy('prodi.nama_jenjang_pendidikan', $orderDirection)
            //         ->orderBy('prodi.nama_program_studi', $orderDirection)
            //         ->select('mata_kuliahs.*', 'prodi.nama_jenjang_pendidikan', 'prodi.nama_program_studi'); // Avoid column name conflicts
            // } else {
                $query = $query->orderBy($columns[$orderColumn], $orderDirection);
            // }
        }

        $data = $query->skip($offset)->take($limit)->get();

        // dd($data);

        $recordsTotal = KuisonerAnswer::count();

        $response = [
            'draw' => $request->input('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ];

        return response()->json($response);
    }

    public function kelas_kuliah($id_prodi)
    {
        // $prodi=$id_prodi->get
        // dd($id_prodi);

        $kelas_kuliah = KelasKuliah::where('id_prodi', $id_prodi)
                    // ->orderBy('fakultas_id', 'ASC')
                    // ->orderBy('id_jenjang_pendidikan', 'ASC')
                    // ->orderBy('nama_program_studi', 'ASC')
                    ->get();
        // $jenisBeasiswa = JenisBeasiswaMahasiswa::all();

        // dd($kelas_kuliah);
        return view('dppm.kuisioner.kelas-kuliah', [
            'prodi' => $kelas_kuliah,
            // 'jenisBeasiswa' => $jenisBeasiswa,
        ]);
    }


    public function kelas_penjadwalan(Request $request, $id_prodi)
    {
        // dd($id_prodi);
        
        $request->validate([
            'semester_view' => 'nullable|exists:semesters,id_semester'
        ]);

        $semester_view = $request->semester_view ?? null;

        $semester_aktif = SemesterAktif::first();

        if ($semester_view != null && !in_array($semester_view, $semester_aktif->semester_allow)) {
            return redirect()->back()->with('error', "Semester Tidak dalam list yang di izinkan!");
        }

        $semester_pilih = $semester_view == null ? $semester_aktif->id_semester : $semester_view;
        $dbSemester = Semester::select('id_semester', 'nama_semester');

        $pilihan_semester = $semester_aktif->semester_allow != null ? $dbSemester->whereIn('id_semester', $semester_aktif->semester_allow)->orderBy('id_semester', 'desc')->get() : $dbSemester->whereIn('id_semester', [$semester_aktif->id_semester])->orderBy('id_semester', 'desc')->get();
        // dd($semester_aktif);
        $prodi_id = $id_prodi;

        $data = ListKurikulum::with(['mata_kuliah' => function ($query) use ($prodi_id, $semester_pilih) {
            $query->with(['matkul_konversi' => function ($query) use ($prodi_id) {
                $query->where('id_prodi', $prodi_id);
            }, 'kelas_kuliah' => function($q) use ($prodi_id, $semester_pilih){
                $q->where('id_prodi', $prodi_id);
                $q->where('id_semester', $semester_pilih);
            }])->withCount(['kelas_kuliah as jumlah_kelas' => function($q) use ($prodi_id, $semester_pilih) {
                $q->where('id_prodi', $prodi_id);
                $q->where('id_semester', $semester_pilih);
            }]);
        }])
        ->where('id_prodi', $prodi_id)
        ->where('is_active', 1)
        ->get();
        // dd($data);
        return view('dppm.kuisioner.prodi.kelas-penjadwalan.index', ['prodi_id'=>$prodi_id, 'data' => $data, 'semester_aktif' => $semester_aktif, 'pilihan_semester'=>$pilihan_semester, 'semester_view'=>$semester_view, 'semester_pilih'=>$semester_pilih]);
    }


    public function kuisioner_kelas($id_kelas)
    {
        // dd($id_kelas);
        $kuisioner = KuisonerAnswer::where('id_kelas_kuliah', $id_kelas)
                    ->with('kuisoner_question')
                    ->get()
                    ->groupBy('kuisoner_question_id');

        // Menghitung jumlah jawaban untuk setiap nilai (1 sampai 7)
        $nilai_counts = KuisonerAnswer::where('id_kelas_kuliah', $id_kelas)
                    ->select('kuisoner_question_id', 'nilai', DB::raw('count(*) as count'))
                    ->groupBy('kuisoner_question_id', 'nilai')
                    ->get()
                    ->groupBy('kuisoner_question_id');

        $kelas = KelasKuliah::where('id_kelas_kuliah', $id_kelas)
                ->with('matkul', 'dosen_pengajar.dosen', 'semester', 'peserta_kelas')
                ->select('id_kelas_kuliah', 'id_matkul', 'nama_kelas_kuliah', 'id_semester')
                ->withCount(['peserta_kelas' => function ($query) {
                    $query->where('approved', 1);
                }])
                ->first();

        return view('dppm.kuisioner.prodi.kelas-penjadwalan.kuisioner', [
                    'kuisioner' => $kuisioner,
                    'nilai_counts' => $nilai_counts,
                    'kelas' => $kelas
                ]);
    }

    public function kuisioner_matkul($id_matkul, $semester)
    {
        // $prodi_id 
        $kelas = KelasKuliah::where('id_matkul', $id_matkul)
                ->where('id_semester', $semester)
                ->select('id_kelas_kuliah')
                ->get()->pluck('id_kelas_kuliah');

        $mata_kuliah = MataKuliah::where('id_matkul', $id_matkul)->first();
        $semester = Semester::where('id_semester', $semester)->first();

        $kuisioner = KuisonerAnswer::whereIn('id_kelas_kuliah', $kelas)
                ->with('kuisoner_question')
                ->get()
                ->groupBy('kuisoner_question_id');

        $nilai_counts = KuisonerAnswer::whereIn('id_kelas_kuliah', $kelas)
                ->select('kuisoner_question_id', 'nilai', DB::raw('count(*) as count'))
                ->groupBy('kuisoner_question_id', 'nilai')
                ->get()
                ->groupBy('kuisoner_question_id');
        // dd($nilai_counts);

        return view('dppm.kuisioner.prodi.kelas-penjadwalan.kuisioner-matkul.index', [
            'nilai_counts' => $nilai_counts,
            'kuisioner' => $kuisioner,
            'mata_kuliah' => $mata_kuliah,
            'semester' => $semester
        ]);
    }

}
