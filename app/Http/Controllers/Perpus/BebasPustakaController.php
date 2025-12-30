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
        $verifikator = [
            "RTS",
            "NO",
        ];
        return view('perpus.bebas-pustaka.index',
        [
            'verifikator' => $verifikator,
        ]);
    }

    public function getData(Request $request)
    {
        $riwayat = RiwayatPendidikan::with(['prodi.fakultas', 'prodi.jurusan', 'pembimbing_akademik'])->where('nim', $request->nim)->orderBy('id_periode_masuk', 'desc')->first();

        $check = BebasPustaka::where('id_registrasi_mahasiswa', $riwayat->id_registrasi_mahasiswa)
            ->whereNotNull('file_bebas_pustaka')
            ->whereNotNull('link_repo')
            ->whereNotNull('verifikator')
            ->first();

        if ($check) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data Bebas Pustaka Mahasiswa sudah lengkap dan tidak dapat ditambahkan lagi!',
            ], 422);
        }

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
        $request->validate([
            'id_registrasi_mahasiswa' => 'required',
            'file_bebas_pustaka'      => 'nullable|file|mimes:pdf|max:1024',
            'link_repo'               => 'nullable|active_url',
            'verifikator'             => 'required',
        ]);

        $userId = auth()->user()->id;

        $riwayat = RiwayatPendidikan::where(
            'id_registrasi_mahasiswa',
            $request->id_registrasi_mahasiswa
        )->firstOrFail();

        // Ambil data lama (jika ada)
        $bebas = BebasPustaka::where(
            'id_registrasi_mahasiswa',
            $request->id_registrasi_mahasiswa
        )->first();

        $data = [
            'verifikator' => $request->verifikator,
            'user_id'     => $userId,
        ];

        // ✅ HANYA update link_repo jika diisi
        if ($request->filled('link_repo')) {
            $data['link_repo'] = $request->link_repo;
        }

        // ✅ HANYA update file jika upload baru
        if ($request->hasFile('file_bebas_pustaka')) {

            $folder = 'bebas-pustaka';

            $file     = $request->file('file_bebas_pustaka');
            $fileName = $riwayat->nim . '.' . $file->getClientOriginalExtension();

            $data['file_bebas_pustaka'] =
                $file->storeAs($folder, $fileName, 'public');
        }

        // 🔥 CREATE / UPDATE TANPA MENGHAPUS DATA LAMA
        if ($bebas) {
            $bebas->update($data);
        } else {
            BebasPustaka::create(array_merge(
                $data,
                ['id_registrasi_mahasiswa' => $request->id_registrasi_mahasiswa]
            ));
        }

        return redirect()
            ->route('perpus.bebas-pustaka')
            ->with('success', 'Data Bebas Pustaka berhasil disimpan.');
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
