<?php

namespace App\Http\Controllers\Fakultas;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa\LulusDo;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\MonitoringIsiKrs;
use App\Models\ProgramStudi;
use App\Models\SemesterAktif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MonitoringController extends Controller
{
    public function pengisian_krs()
    {
        $id_prodi_fak = ProgramStudi::where('fakultas_id', auth()->user()->fk_id)
                    ->orderBy('id_jenjang_pendidikan')
                    ->orderBy('nama_program_studi')
                    ->pluck('id_prodi', 'nama_program_studi');
        

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
        $db = new LulusDo();
        $jenis_keluar = $db->select('id_jenis_keluar', 'nama_jenis_keluar')->distinct()->get();

        $jenis_keluar_counts = $db->select('id_jenis_keluar','nama_jenis_keluar', DB::raw('count(*) as total'))
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

        $prodi = ProgramStudi::orderBy('kode_program_studi')->get();
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
        $searchValue = $request->input('search.value');

        $query = LulusDo::with('prodi', 'biodata');

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

        $recordsTotal = LulusDo::count();

        $response = [
            'draw' => $request->input('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ];

        return response()->json($response);
    }
}
