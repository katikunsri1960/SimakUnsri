<?php

namespace App\Http\Controllers\Perpus;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Perpus\BebasPustaka;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BebasPustakaController extends Controller
{
    public function index()
    {
        return view('perpus.bebas-pustaka.index');
    }

    public function getData(Request $request)
    {

        $riwayat = RiwayatPendidikan::with(['prodi.fakultas', 'prodi.jurusan', 'pembimbing_akademik'])->where('nim', $request->nim)->orderBy('id_periode_masuk', 'desc')->first();

        if(!$riwayat) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data Mahasiswa tidak ditemukan!!',
            ]);
        }


        return response()->json([
            'status' => 'success',
            'riwayat' => $riwayat
        ]);

    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_registrasi_mahasiswa' => 'required|unique:bebas_pustakas,id_registrasi_mahasiswa',
            'file_bebas_pustaka' => 'required|file|mimes:pdf|max:1024',
            'link_repo' => 'required|active_url',
        ]);

        $data['user_id'] = auth()->user()->id;

        // Define the folder within the public disk
        $folder = 'bebas-pustaka';

        // Ensure the folder exists (not necessary for public disk, but included for completeness)
        if (!Storage::disk('public')->exists($folder)) {
            Storage::disk('public')->makeDirectory($folder);
        }

        $riwayat = RiwayatPendidikan::where('id_registrasi_mahasiswa', $data['id_registrasi_mahasiswa'])->first();

        // Generate the file name using $riwayat->nim
        $file = $request->file('file_bebas_pustaka');
        $fileName = $riwayat->nim . '.' . $file->getClientOriginalExtension();

        // Store the file in the specified folder with the new file name on the public disk
        $data['file_bebas_pustaka'] = $file->storeAs($folder, $fileName, 'public');

        BebasPustaka::create($data);

        return redirect()->route('perpus.bebas-pustaka')->with('success', 'Data Bebas Pustaka berhasil ditambahkan');
    }

    public function delete(BebasPustaka $bebasPustaka)
    {
        // Check if the file exists and delete it
        if ($bebasPustaka->file_bebas_pustaka && Storage::disk('public')->exists($bebasPustaka->file_bebas_pustaka)) {
            Storage::disk('public')->delete($bebasPustaka->file_bebas_pustaka);
        }

        // Delete the record from the database
        $bebasPustaka->delete();

        return redirect()->route('perpus.bebas-pustaka')->with('success', 'Data Bebas Pustaka berhasil dihapus');
    }

    public function list()
    {
        $prodi = ProgramStudi::where('status', 'A')->orderBy('kode_program_studi')->get();
        return view('perpus.bebas-pustaka.list', [
            'prodi' => $prodi,
        ]);
    }

    public function listData(Request $request)
    {
        $searchValue = $request->input('search.value');

        $query = BebasPustaka::join('riwayat_pendidikans as r', 'r.id_registrasi_mahasiswa', 'bebas_pustakas.id_registrasi_mahasiswa')
                ->join('program_studis as prodi', 'r.id_prodi', 'prodi.id_prodi')
                ->join('users as u', 'u.id', 'bebas_pustakas.user_id')
                ->select('bebas_pustakas.*', 'r.nim as nim', 'r.nama_mahasiswa as nama_mahasiswa', 'u.name as nama_user', 'prodi.nama_program_studi as nama_program_studi', 'prodi.nama_jenjang_pendidikan as nama_jenjang_pendidikan');

        if ($searchValue) {
            $query = $query->where('r.nim', 'like', '%' . $searchValue . '%')
                ->orWhere('r.nama_mahasiswa', 'like', '%' . $searchValue . '%');
        }

        if ($request->has('prodi') && !empty($request->prodi)) {
            $filter = $request->prodi;
            $query->whereIn('r.id_prodi', $filter);
        }

        $recordsFiltered = $query->count();

        $limit = $request->input('length');
        $offset = $request->input('start');

        // Define the column names that correspond to the DataTables column indices
        if ($request->has('order')) {
            $orderColumn = $request->input('order.0.column');
            $orderDirection = $request->input('order.0.dir');

            // Define the column names that correspond to the DataTables column indices
            $columns = ['nama_program_studi', 'nim', 'nama_mahasiswa'];

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

        $recordsTotal = BebasPustaka::count();

        $response = [
            'draw' => $request->input('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ];

        return response()->json($response);
    }
}
