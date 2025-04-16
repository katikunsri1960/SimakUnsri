<?php

namespace App\Http\Controllers\Bak;

use App\Http\Controllers\Controller;
use App\Models\BeasiswaMahasiswa;
use App\Models\JenisBeasiswaMahasiswa;
use App\Models\ProgramStudi;
use App\Models\SemesterAktif;
use Illuminate\Http\Request;

class BeasiswaController extends Controller
{
    public function index(Request $request)
    {
        $prodi = ProgramStudi::all();
        $jenisBeasiswa = JenisBeasiswaMahasiswa::all();

        return view('bak.beasiswa.index', [
            'prodi' => $prodi,
            'jenisBeasiswa' => $jenisBeasiswa,
        ]);
    }

    public function data(Request $request)
    {
        $searchValue = $request->input('search.value');

        $semesterAktif = SemesterAktif::first();

        $query = BeasiswaMahasiswa::with(['mahasiswa.prodi', 'jenis_beasiswa', 'aktivitas_kuliah' => function ($query) use ($semesterAktif) {
            $query->where('id_semester', $semesterAktif->id_semester);
        },
        ])
            // ->where('beasiswa_mahasiswas.nim', '08051282328058')
            ->join('riwayat_pendidikans', 'beasiswa_mahasiswas.id_registrasi_mahasiswa', '=', 'riwayat_pendidikans.id_registrasi_mahasiswa')
            ->leftJoin('pembiayaans', 'beasiswa_mahasiswas.id_pembiayaan', '=', 'pembiayaans.id_pembiayaan')
            ->select('beasiswa_mahasiswas.*', 'riwayat_pendidikans.nama_program_studi as nama_program_studi', 'riwayat_pendidikans.id_periode_masuk as id_periode_masuk', 'pembiayaans.nama_pembiayaan as nama_pembiayaan');

        // dd($query);

        if ($searchValue) {
            $query = $query->where('beasiswa_mahasiswas.nim', 'like', '%'.$searchValue.'%')
                ->orWhere('beasiswa_mahasiswas.nama_mahasiswa', 'like', '%'.$searchValue.'%');
        }

        if ($request->has('prodi') && ! empty($request->prodi)) {
            $filter = $request->prodi;
            $query->whereIn('id_prodi', $filter);
        }

        if ($request->has('jenis_beasiswa') && ! empty($request->jenis_beasiswa)) {
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

        $recordsTotal = BeasiswaMahasiswa::count();

        $response = [
            'draw' => $request->input('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ];

        return response()->json($response);
    }
}
