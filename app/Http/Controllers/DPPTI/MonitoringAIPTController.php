<?php

namespace App\Http\Controllers\DPPTI;

use Carbon\Carbon;
use App\Models\Fakultas;
use App\Models\Semester;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use App\Models\SemesterAktif;
use App\Models\PenundaanBayar;
use App\Models\MonitoringIsiKrs;
use App\Models\Mahasiswa\LulusDo;
use App\Models\Connection\Tagihan;
use App\Models\Dosen\BiodataDosen;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Connection\Registrasi;
use App\Models\Perkuliahan\KelasKuliah;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Monitoring\MonevStatusMahasiswa;
use App\Models\Perkuliahan\DosenPengajarKelasKuliah;
use App\Models\Monitoring\MonevStatusMahasiswaDetail;
use App\Models\Perkuliahan\ListKurikulum;

class MonitoringAIPTController extends Controller
{

    public function mahasiswa_aipt (Request $request)
    {
        $db = new LulusDo();
        $semester_aktif = SemesterAktif::first()->id_semester;
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

        if($request->has('periode_keluar') && !empty($request->periode_keluar)) {
            $filter = $request->periode_keluar;
            $jenis_keluar_counts->whereIn('id_periode_keluar', $filter);
        }

        $jenis_keluar_counts = $jenis_keluar_counts->get();

        $prodi = ProgramStudi::orderBy('kode_program_studi')->get();
        $angkatan = $db->select('angkatan')->distinct()->orderBy('angkatan', 'desc')->get();
        $periode_keluar = Semester::select('id_semester', 'nama_semester')
                        ->where('id_semester', '<=', $semester_aktif)
                        ->whereNot('semester', 3)
                        // ->limit(10)
                        ->orderBy('id_semester', 'desc')->get();

                        // dd($periode_keluar);
                        // dd($jenis_keluar);
        return view('dppti.monitoring.status-aipt.mahasiswa.index', [
            'jenis_keluar' => $jenis_keluar,
            'jenis_keluar_counts' => $jenis_keluar_counts,
            'prodi' => $prodi,
            'angkatan' => $angkatan,
            'periode_keluar' => $periode_keluar
        ]);
    }

    public function mahasiswa_aipt_data(Request $request)
    {
        // dd($request->all());

        $searchValue = $request->input('search.value');

        $query = LulusDo::with([
            'prodi',
            'biodata',
            'periode_keluar'
        ]);

        /*
        |--------------------------------------------------------------------------
        | Search DataTables
        |--------------------------------------------------------------------------
        */
        if (!empty($searchValue)) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('nim', 'like', '%' . $searchValue . '%')
                    ->orWhere('nama_mahasiswa', 'like', '%' . $searchValue . '%')
                    ->orWhere('nama_program_studi', 'like', '%' . $searchValue . '%');
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Filter Program Studi
        |--------------------------------------------------------------------------
        */
        if ($request->filled('id_prodi')) {
            $query->whereIn(
                'id_prodi',
                (array) $request->input('id_prodi')
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Filter Angkatan
        |--------------------------------------------------------------------------
        */
        if ($request->filled('angkatan')) {
            $query->whereIn(
                'angkatan',
                (array) $request->input('angkatan')
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Filter Jenis Keluar
        |--------------------------------------------------------------------------
        */
        if ($request->filled('jenis_keluar')) {
            $query->whereIn(
                'id_jenis_keluar',
                (array) $request->input('jenis_keluar')
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Filter Periode Keluar
        |--------------------------------------------------------------------------
        */
        if ($request->filled('periode_keluar')) {
            $query->whereIn(
                'id_periode_keluar',
                (array) $request->input('periode_keluar')
            );
        }

        // dd($request->input('id_periode_keluar'));

        /*
        |--------------------------------------------------------------------------
        | Total setelah filter
        |--------------------------------------------------------------------------
        */
        $recordsFiltered = $query->count();

        /*
        |--------------------------------------------------------------------------
        | DataTables Pagination
        |--------------------------------------------------------------------------
        */
        $limit = (int) $request->input('length', 10);
        $offset = (int) $request->input('start', 0);

        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        */
        $columns = [
            'nim',
            'nama_mahasiswa',
            'nama_program_studi',
            'angkatan',
            'tanggal_keluar',
            'nm_smt',
            'keterangan'
        ];

        if ($request->has('order')) {

            $orderColumn = (int) $request->input('order.0.column');
            $orderDirection = $request->input('order.0.dir', 'asc');

            // Pastikan index kolom valid
            if (isset($columns[$orderColumn])) {

                // Pastikan direction hanya asc / desc
                $orderDirection = in_array(
                    strtolower($orderDirection),
                    ['asc', 'desc']
                )
                    ? strtolower($orderDirection)
                    : 'asc';

                $query->orderBy(
                    $columns[$orderColumn],
                    $orderDirection
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Ambil Data
        |--------------------------------------------------------------------------
        */
        $data = $query
            ->skip($offset)
            ->take($limit)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Total seluruh data
        |--------------------------------------------------------------------------
        */
        $recordsTotal = LulusDo::count();

        /*
        |--------------------------------------------------------------------------
        | Response DataTables
        |--------------------------------------------------------------------------
        */
        return response()->json([
            'draw' => (int) $request->input('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    public function dosen_aipt()
    {
        $semesterAktif = SemesterAktif::first()->id_semester;
        $tahunAjaran = substr($semesterAktif, 0, 4);

        $data = BiodataDosen::with(['gelar','penugasan_terbaru' => function ($query) use ($tahunAjaran) {
                $query->where('id_tahun_ajaran', $tahunAjaran);
            }])
            ->whereHas('penugasan_terbaru', function ($query) use ($tahunAjaran) {
                $query->where('id_tahun_ajaran', $tahunAjaran);
            })
            ->orderBy('nama_dosen', 'ASC')
            // ->limit(10)
            ->get();

        // dd($data[15]->penugasan_terbaru);

        return view('dppti.monitoring.status-aipt.dosen.index', [
            'data' => $data
        ]);
    }

}
