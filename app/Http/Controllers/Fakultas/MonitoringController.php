<?php

namespace App\Http\Controllers\Fakultas;

use App\Models\Fakultas;
use App\Models\Semester;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use App\Models\SemesterAktif;
use App\Models\MonitoringIsiKrs;
use App\Models\Mahasiswa\LulusDo;
use App\Models\Dosen\BiodataDosen;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Perkuliahan\KelasKuliah;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Perkuliahan\DosenPengajarKelasKuliah;

class MonitoringController extends Controller
{
    public function pengisian_krs()
    {
        $id_prodi_fak = ProgramStudi::where('fakultas_id', auth()->user()->fk_id)
                    ->where('status', 'A')
                    ->orderBy('id_jenjang_pendidikan')
                    ->orderBy('nama_program_studi')
                    ->pluck('id_prodi');
        // $id_prodi_fak=$prodi_fak->pluck('id_prodi');
        
        $data = MonitoringIsiKrs::with(['prodi'])
                ->join('program_studis', 'monitoring_isi_krs.id_prodi', 'program_studis.id_prodi')
                ->join('fakultas', 'fakultas.id', 'program_studis.fakultas_id')
                ->whereIn('program_studis.id_prodi', $id_prodi_fak)
                ->orderBy('program_studis.id_jenjang_pendidikan')
                ->orderBy('program_studis.kode_program_studi')
                ->get();
                // dd($id_prodi_fak);

        return view('fakultas.monitoring.pengisian-krs.index', [
            'data' => $data,
        ]);
    }

    public function detail_mahasiswa_aktif(ProgramStudi $prodi)
    {
        $id_prodi = $prodi->id_prodi;

        $data = RiwayatPendidikan::where('id_prodi', $id_prodi)
                ->whereNull('id_jenis_keluar')
                ->orderBy('id_periode_masuk', 'ASC')
                ->get();

        return view('fakultas.monitoring.pengisian-krs.detail-mahasiswa-aktif', [
            'prodi' => $prodi,
            'data' => $data
        ]);
    }

    public function detail_aktif_min_tujuh(ProgramStudi $prodi)
    {
        $id_prodi = $prodi->id_prodi;

        $angkatanAktif = date('Y') - 7;
        $arrayTahun = range($angkatanAktif, date('Y'));

        $data = RiwayatPendidikan::where('id_prodi', $id_prodi)
                ->whereNull('id_jenis_keluar')
                ->whereIn(DB::raw('LEFT(id_periode_masuk, 4)'), $arrayTahun)
                ->orderBy('id_periode_masuk', 'ASC')
                ->get();

        return view('fakultas.monitoring.pengisian-krs.detail-aktif-min-tujuh', [
            'prodi' => $prodi,
            'data' => $data
        ]);
    }

    public function detail_isi_krs(ProgramStudi $prodi)
    {
        $id_prodi = $prodi->id_prodi;
        $semesterAktif = SemesterAktif::first()->id_semester;

        $db = new RiwayatPendidikan();

        $data = $db->detail_isi_krs($id_prodi, $semesterAktif);

        return view('fakultas.monitoring.pengisian-krs.detail-isi-krs', [
            'prodi' => $prodi,
            'data' => $data
        ]);
    }

    public function detail_approved_krs(ProgramStudi $prodi)
    {
        $id_prodi = $prodi->id_prodi;
        $semesterAktif = SemesterAktif::first()->id_semester;
        $db = new RiwayatPendidikan();

        $data = $db->krs_data($id_prodi, $semesterAktif, 1);

        return view('fakultas.monitoring.pengisian-krs.approve-krs', [
            'prodi' => $prodi,
            'data' => $data
        ]);
    }

    public function detail_not_approved_krs(ProgramStudi $prodi)
    {
        $id_prodi = $prodi->id_prodi;
        $semesterAktif = SemesterAktif::first()->id_semester;
        $db = new RiwayatPendidikan();
        $data = $db->krs_data($id_prodi, $semesterAktif, 0);

        return view('fakultas.monitoring.pengisian-krs.not-approve-krs', [
            'prodi' => $prodi,
            'data' => $data
        ]);
    }

    public function tidak_isi_krs(ProgramStudi $prodi)
    {

        $id_prodi = $prodi->id_prodi;
        $semesterAktif = SemesterAktif::first()->id_semester;

        $db = new RiwayatPendidikan();
        $data = $db->tidak_isi_krs($id_prodi, $semesterAktif);

        return view('fakultas.monitoring.pengisian-krs.tidak-isi-krs', [
            'prodi' => $prodi,
            'data' => $data
        ]);
    }

    public function mahasiswa_up_tujuh(ProgramStudi $prodi)
    {
        $id_prodi = $prodi->id_prodi;

        $angkatanAktif = date('Y') - 7;
        $arrayTahun = range($angkatanAktif, date('Y'));

        $data = RiwayatPendidikan::where('id_prodi', $id_prodi)
                ->whereNull('id_jenis_keluar')
                ->whereNotIn(DB::raw('LEFT(id_periode_masuk, 4)'), $arrayTahun)
                ->orderBy('id_periode_masuk', 'ASC')
                ->get();

        return view('fakultas.monitoring.pengisian-krs.mahasiswa-up-tujuh', [
            'prodi' => $prodi,
            'data' => $data
        ]);
    }

    public function lulus_do(Request $request)
    {
        $id_prodi_fak = ProgramStudi::where('fakultas_id', auth()->user()->fk_id)
                    ->where('status', 'A')
                    ->orderBy('id_jenjang_pendidikan')
                    ->orderBy('nama_program_studi')
                    ->pluck('id_prodi');
        // $id_prodi_fak=$prodi_fak->pluck('id_prodi');
        
        
        $db = new LulusDo();
        $jenis_keluar = $db->select('id_jenis_keluar', 'nama_jenis_keluar')->distinct()->get();

        $jenis_keluar_counts = $db->select('id_jenis_keluar','nama_jenis_keluar', DB::raw('count(*) as total'))
        ->whereIn('id_prodi', $id_prodi_fak)
        ->groupBy('id_jenis_keluar','nama_jenis_keluar');

        if ($request->has('id_prodi') && !empty($request->id_prodi)) {
            $filter = $request->id_prodi;
            $jenis_keluar_counts->whereIn('id_prodi', $filter);
        }

        if ($request->has('angkatan') && !empty($request->angkatan)) {
            $filter = $request->angkatan;
            $jenis_keluar_counts->whereIn('angkatan', $filter);
        }

        if($request->has('jenis_keluar') && !empty($request->jenis_keluar)) {
            $filter = $request->jenis_keluar;
            $jenis_keluar_counts->whereIn('id_jenis_keluar', $filter);
        }

        $jenis_keluar_counts = $jenis_keluar_counts->get();

        $prodi = ProgramStudi::whereIn('id_prodi', $id_prodi_fak)->orderBy('nama_jenjang_pendidikan')->orderBy('nama_program_studi')->get();
        $angkatan = $db->select('angkatan')->distinct()->orderBy('angkatan', 'desc')->get();

        return view('fakultas.monitoring.kelulusan.index', [
            'jenis_keluar' => $jenis_keluar,
            'jenis_keluar_counts' => $jenis_keluar_counts,
            'prodi' => $prodi,
            'angkatan' => $angkatan
        ]);
    }

    public function lulus_do_data(Request $request)
    {
        $id_prodi_fak = ProgramStudi::where('fakultas_id', auth()->user()->fk_id)
                    ->where('status', 'A')
                    ->orderBy('id_jenjang_pendidikan')
                    ->orderBy('nama_program_studi')
                    ->pluck('id_prodi');

        $searchValue = $request->input('search.value');

        $query = LulusDo::with('prodi', 'biodata')
                ->whereIn('id_prodi', $id_prodi_fak);

        if ($searchValue) {
            $query = $query->where('nim', 'like', '%' . $searchValue . '%')
                ->orWhere('nama_mahasiswa', 'like', '%' . $searchValue . '%')
                ->orWhere('nama_program_studi', 'like', '%' . $searchValue . '%');
        }

        if ($request->has('id_prodi') && !empty($request->id_prodi)) {
            $filter = $request->id_prodi;
            $query->whereIn('id_prodi', $filter);
        }

        if ($request->has('angkatan') && !empty($request->angkatan)) {
            $filter = $request->angkatan;
            $query->whereIn('angkatan', $filter);
        }

        if($request->has('jenis_keluar') && !empty($request->jenis_keluar)) {
            $filter = $request->jenis_keluar;
            $query->whereIn('id_jenis_keluar', $filter);
        }

        $recordsFiltered = $query->count();

        $limit = $request->input('length');
        $offset = $request->input('start');

        // Define the column names that correspond to the DataTables column indices
        if ($request->has('order')) {
            $orderColumn = $request->input('order.0.column');
            $orderDirection = $request->input('order.0.dir');

            // Define the column names that correspond to the DataTables column indices
            $columns = ['nim','nama_mahasiswa', 'nama_program_studi', 'angkatan', 'tanggal_keluar', 'nm_smt', 'keterangan'];

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

        $recordsTotal = LulusDo::whereIn('id_prodi', $id_prodi_fak)->count();

        $response = [
            'draw' => $request->input('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ];

        return response()->json($response);
    }

    // MONITORING PENGISIAN NILAI
    public function pengisian_nilai()
    {
        $prodi = ProgramStudi::where('fakultas_id', auth()->user()->fk_id)
                            ->where('status', 'A')
                            ->orderBy('id_jenjang_pendidikan', 'ASC')
                            ->orderBy('nama_program_studi')
                            ->get();
        // dd($prodi);
        $semesterAktif = SemesterAktif::first()->id_semester;
        $semester = Semester::where('id_semester', '<=', $semesterAktif)->orderBy('id_semester', 'desc')->get();

        return view('fakultas.monitoring.pengisian-nilai.index', [
            'prodi' => $prodi,
            'semester' => $semester,
            'semesterAktif' => $semesterAktif
        ]);
    }

    public function pengisian_nilai_detail(string $mode, string $dosen, string $prodi)
    {
        $semesterAktif = SemesterAktif::first()->id_semester;
        $id_prodi = ProgramStudi::find($prodi)->id_prodi;
        $biodataDosen = BiodataDosen::where('id_dosen', $dosen)
                ->select('nidn', 'nama_dosen')
                ->first();

        $db = new DosenPengajarKelasKuliah();

        $titles = [
            1 => "Total Kelas Ajar",
            2 => "Kelas Sudah Dinilai",
            3 => "Kelas Belum Dinilai"
        ];

        $title = $titles[$mode] ?? "Unknown Mode";

        $query = $db->join('kelas_kuliahs as k', 'k.id_kelas_kuliah', 'dosen_pengajar_kelas_kuliahs.id_kelas_kuliah')
            ->join('biodata_dosens as d', 'd.id_dosen', 'dosen_pengajar_kelas_kuliahs.id_dosen')
            ->where('k.id_semester', $semesterAktif)
            ->where('dosen_pengajar_kelas_kuliahs.urutan', 1)
            ->where('k.id_prodi', $id_prodi)
            ->where('d.id_dosen', $dosen)
            ->whereRaw('EXISTS (SELECT * FROM peserta_kelas_kuliahs WHERE peserta_kelas_kuliahs.id_kelas_kuliah = k.id_kelas_kuliah)');

        if ($mode == 2) {
            $query->whereRaw('EXISTS (SELECT 1 FROM nilai_perkuliahans WHERE nilai_perkuliahans.id_kelas_kuliah = k.id_kelas_kuliah)');
        } elseif ($mode == 3) {
            $query->whereRaw('NOT EXISTS (SELECT 1 FROM nilai_perkuliahans WHERE nilai_perkuliahans.id_kelas_kuliah = k.id_kelas_kuliah)');
        }

        $id_kelas = $query->select('k.id_kelas_kuliah')
                    ->distinct()
                    ->pluck('k.id_kelas_kuliah');

        $data = KelasKuliah::whereIn('id_kelas_kuliah', $id_kelas)
            ->with(['matkul', 'prodi', 'dosen_pengajar','peserta_kelas' => function ($query) {
                $query->where('approved', 1);
            }])
            ->get();

        return view('fakultas.monitoring.pengisian-nilai.detail', [
            'title' => $title,
            'data' => $data,
            'dosen' => $biodataDosen,
        ]);

    }

    public function pengisian_nilai_data(Request $request)
    {
        $prodi = $request->input('prodi');
        $id_prodi = ProgramStudi::find($prodi)->id_prodi;
        $semester = '20241';

        $db = new DosenPengajarKelasKuliah();

        // Query untuk mendapatkan data dosen dan jumlah kelas yang dinilai dan belum dinilai
        $data = $db->join('kelas_kuliahs as k', 'k.id_kelas_kuliah', 'dosen_pengajar_kelas_kuliahs.id_kelas_kuliah')
            ->join('biodata_dosens as d', 'd.id_dosen', 'dosen_pengajar_kelas_kuliahs.id_dosen')
            ->where('k.id_semester', $semester)
            ->where('dosen_pengajar_kelas_kuliahs.urutan', 1)
            ->where('k.id_prodi', $id_prodi)
            ->whereRaw('EXISTS (SELECT * FROM peserta_kelas_kuliahs WHERE peserta_kelas_kuliahs.id_kelas_kuliah = k.id_kelas_kuliah)')
            ->select(
                'dosen_pengajar_kelas_kuliahs.id_dosen',
                'd.nidn',
                'd.nama_dosen',
                DB::raw('COUNT(k.id_kelas_kuliah) as total_kelas'),
                DB::raw('SUM(CASE WHEN EXISTS (SELECT 1 FROM nilai_perkuliahans WHERE nilai_perkuliahans.id_kelas_kuliah = k.id_kelas_kuliah) THEN 1 ELSE 0 END) as total_kelas_dinilai')
            )
            ->groupBy('dosen_pengajar_kelas_kuliahs.id_dosen', 'd.nidn', 'd.nama_dosen')
            ->get();

        // Proses data untuk menghitung total kelas yang belum dinilai
        $dataAccumulation = $data->map(function ($item) {
            $item->total_kelas_belum_dinilai = $item->total_kelas - $item->total_kelas_dinilai;
            return $item;
        });

        $response = [
            'status' => 'success',
            'data' => $dataAccumulation
        ];

        return response()->json($response);
    }
}
