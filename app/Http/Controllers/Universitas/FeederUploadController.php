<?php

namespace App\Http\Controllers\Universitas;

use App\Http\Controllers\Controller;
use App\Models\Perkuliahan\AktivitasKuliahMahasiswa;
use Illuminate\Http\Request;

class FeederUploadController extends Controller
{

    public function akm()
    {
        $count = AktivitasKuliahMahasiswa::where('feeder', 0)->count();

        return view('universitas.feeder-upload.akm.index',
        [
            'count' => $count
        ]);
    }

    public function akm_data(Request $request)
    {
        $searchValue = $request->input('search.value');

        $query = AktivitasKuliahMahasiswa::with('pembiayaan')->where('feeder', 0)->leftJoin('pembiayaans', 'aktivitas_kuliah_mahasiswas.id_pembiayaan', 'pembiayaans.id_pembiayaan');

        if ($searchValue) {
            $query = $query->where('nim', 'like', '%' . $searchValue . '%')
                ->orWhere('nama_mahasiswa', 'like', '%' . $searchValue . '%');
        }

        if ($request->has('id_prodi') && !empty($request->id_prodi)) {
            $filter = $request->id_prodi;
            $query->whereIn('id_prodi', $filter);
        }

        if ($request->has('semester') && !empty($request->semester)) {
            $filter = $request->semester;
            $query->whereIn('id_semester', $filter);
        }

        if ($request->has('angkatan') && !empty($request->angkatan)) {
            $filter = $request->angkatan;
            $query->whereIn('angkatan', $filter);
        }

        if ($request->has('status_mahasiswa') && !empty($request->status_mahasiswa)) {
            $filter = $request->status_mahasiswa;
            $query->whereIn('id_status_mahasiswa', $filter);
        }

        $recordsFiltered = $query->count();

        $limit = $request->input('length');
        $offset = $request->input('start');

        // Define the column names that correspond to the DataTables column indices
        if ($request->has('order')) {
            $orderColumn = $request->input('order.0.column');
            $orderDirection = $request->input('order.0.dir');

            // Define the column names that correspond to the DataTables column indices
            $columns = ['nim','nama_mahasiswa', 'nama_program_studi', 'angkatan', 'nama_semester', 'nama_status_mahasiswa', 'ips', 'ipk', 'sks_semester', 'sks_total'];

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

        $recordsTotal = AktivitasKuliahMahasiswa::where('feeder', 0)->count();

        $response = [
            'draw' => $request->input('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ];

        return response()->json($response);
    }

    public function kelas()
    {
        return view('universitas.feeder-upload.kelas.index');
    }

    public function data(Request $request)
    {

    }
}
