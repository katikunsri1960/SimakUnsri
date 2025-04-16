<?php

namespace App\Http\Controllers\Fakultas\LainLain;

use App\Http\Controllers\Controller;
use App\Models\BeasiswaMahasiswa;
use App\Models\JenisBeasiswaMahasiswa;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;

class BeasiswaController extends Controller
{
    public function index(Request $request)
    {
        $prodi_fak = ProgramStudi::where('fakultas_id', auth()->user()->fk_id)
            ->pluck('id_prodi');

        $prodi = ProgramStudi::whereIn('id_prodi', $prodi_fak)
            ->where('status', 'A')
            ->orderBy('id_jenjang_pendidikan')
            ->orderBy('nama_program_studi')
            ->get();

        $jenisBeasiswa = JenisBeasiswaMahasiswa::all();

        return view('fakultas.lain-lain.beasiswa.index', [
            'prodi' => $prodi,
            'jenisBeasiswa' => $jenisBeasiswa,
        ]);
    }

    public function data(Request $request)
    {
        $searchValue = $request->input('search.value');

        $prodi_fak = ProgramStudi::where('fakultas_id', auth()->user()->fk_id)
            ->orderBy('id_jenjang_pendidikan')
            ->orderBy('nama_program_studi')
            ->pluck('id_prodi');

        $query = BeasiswaMahasiswa::with('mahasiswa.prodi', 'jenis_beasiswa')
            ->join('riwayat_pendidikans', 'beasiswa_mahasiswas.id_registrasi_mahasiswa', '=', 'riwayat_pendidikans.id_registrasi_mahasiswa')
            ->leftJoin('pembiayaans', 'beasiswa_mahasiswas.id_pembiayaan', '=', 'pembiayaans.id_pembiayaan')
            ->select('beasiswa_mahasiswas.*', 'riwayat_pendidikans.nama_program_studi as nama_program_studi', 'riwayat_pendidikans.id_periode_masuk as id_periode_masuk', 'pembiayaans.nama_pembiayaan as nama_pembiayaan')
            ->whereIn('id_prodi', $prodi_fak);

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
