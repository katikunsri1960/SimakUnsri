<?php

namespace App\Http\Controllers\Prodi\Akademik;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\SemesterAktif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KRSController extends Controller
{
    public function krs()
    {
        $semester = SemesterAktif::with(['semester'])->first();

        return view('prodi.data-akademik.krs.index', [
            'semester' => $semester,
        ]);
    }

    public function data(Request $request)
    {

        $searchValue = $request->input('search.value');
        $semester = SemesterAktif::with(['semester'])->first();
        // dd($semester);
        $query = RiwayatPendidikan::with(['prodi', 'peserta_kelas'])
                ->withCount(['peserta_kelas' => function($query) use ($semester) {
                    $query->whereHas('kelas_kuliah', function($query) use ($semester) {
                        $query->where('id_semester', $semester->id_semester-10);
                    });
                }])
                ->whereHas('peserta_kelas', function($query) use ($semester) {
                    $query->whereHas('kelas_kuliah', function($query) use ($semester) {
                        $query->where('id_semester', $semester->id_semester-10);
                    });
                })
                ->where('id_prodi', auth()->user()->fk_id);

        // $query = RiwayatPendidikan::with('kurikulum', 'pembimbing_akademik')
        //     ->where('id_prodi', auth()->user()->fk_id)
        //     ->orderBy('id_periode_masuk', 'desc'); // Pastikan orderBy di sini

        if ($searchValue) {
            $query = $query->where(function($q) use ($searchValue) {
                $q->where('nim', 'like', '%' . $searchValue . '%')
                ->orWhere('nama_mahasiswa', 'like', '%' . $searchValue . '%');
            });
        }

        if ($request->has('angkatan') && !empty($request->angkatan)) {
            $filter = $request->angkatan;
            $query->whereIn(DB::raw('LEFT(id_periode_masuk, 4)'), $filter);
        }

        $limit = $request->input('length');
        $offset = $request->input('start');

        $data = $query->get();
        dd($data);

        if ($request->has('order')) {
            $orderColumn = $request->input('order.0.column');
            $orderDirection = $request->input('order.0.dir');

            $columns = ['angkatan', 'nim', 'nama_mahasiswa'];

            if ($columns[$orderColumn] == 'angkatan') {
                if ($orderDirection == 'asc') {
                    $data = $data->sortBy(function($item) {
                        return substr($item->id_periode_masuk, 0, 4);
                    })->values();
                } else {
                    $data = $data->sortByDesc(function($item) {
                        return substr($item->id_periode_masuk, 0, 4);
                    })->values();
                }
            } else {
                if ($orderDirection == 'asc') {
                    $data = $data->sortBy($columns[$orderColumn])->values();
                } else {
                    $data = $data->sortByDesc($columns[$orderColumn])->values();
                }
            }
        }

        $recordsFiltered = $data->count();

        $data = $data->slice($offset, $limit)->values();

        $recordsTotal = RiwayatPendidikan::where('id_prodi', auth()->user()->fk_id)->count();

        $response = [
            'draw' => $request->input('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ];

        return response()->json($response);
    }
}
