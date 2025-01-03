<?php

namespace App\Http\Controllers\Universitas;

use App\Http\Controllers\Controller;
use App\Imports\BeasiswaImport;
use App\Models\BeasiswaMahasiswa;
use App\Models\JenisBeasiswaMahasiswa;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Referensi\Pembiayaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class BeasiswaController extends Controller
{
    public function index()
    {
        $jenis = JenisBeasiswaMahasiswa::all();
        $pembiayaan = Pembiayaan::all();
        return view('universitas.beasiswa.index', [
            'jenis' => $jenis,
            'pembiayaan' => $pembiayaan,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_registrasi_mahasiswa' => 'required',
            'id_jenis_beasiswa' => 'required|exists:jenis_beasiswa_mahasiswas,id',
            'id_pembiayaan' => 'required|exists:pembiayaans,id_pembiayaan',
            'tanggal_mulai_beasiswa' => 'required',
            'tanggal_akhir_beasiswa' => 'required',
        ]);

        $riwayat = RiwayatPendidikan::where('id_registrasi_mahasiswa', $data['id_registrasi_mahasiswa'])->orderBy('id_periode_masuk', 'desc')->first();

        if (!$riwayat) {
            return redirect()->back()->with('error', "Data mahasiswa dengan NIM tersebut tidak ditemukan!!");
        }

        $check = BeasiswaMahasiswa::where('id_registrasi_mahasiswa', $data['id_registrasi_mahasiswa'])->first();

        if ($check) {
            return redirect()->back()->with('error', 'Data beasiswa mahasiswa sudah ada.');
        }

        $data['nim'] = $riwayat->nim;
        $data['nama_mahasiswa'] = $riwayat->nama_mahasiswa;

        try {
            DB::beginTransaction();

            BeasiswaMahasiswa::create($data);

            DB::commit();

        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Gagal menyimpan data beasiswa mahasiswa. '.$th->getMessage());
        }

        return redirect()->back()->with('success', 'Data beasiswa mahasiswa berhasil disimpan.');
    }

    public function update(BeasiswaMahasiswa $beasiswa, Request $request)
    {
        $data = $request->validate([
            'id_jenis_beasiswa' => 'required|exists:jenis_beasiswa_mahasiswas,id',
            'id_pembiayaan' => 'required|exists:pembiayaans,id_pembiayaan',
            'tanggal_mulai_beasiswa' => 'required',
            'tanggal_akhir_beasiswa' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $beasiswa->update($data);

            DB::commit();

        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Gagal menyimpan data beasiswa mahasiswa. '.$th->getMessage());
        }

        return redirect()->back()->with('success', 'Data beasiswa mahasiswa berhasil disimpan.');
    }

    public function delete(BeasiswaMahasiswa $beasiswa)
    {
        try {
            DB::beginTransaction();
            $beasiswa->delete();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus data beasiswa mahasiswa. '.$th->getMessage());
        }

        return redirect()->back()->with('success', 'Data beasiswa mahasiswa berhasil dihapus.');
    }

    public function data(Request $request)
    {
        $searchValue = $request->input('search.value');

        $query = BeasiswaMahasiswa::with('mahasiswa.prodi', 'jenis_beasiswa')
                ->join('riwayat_pendidikans', 'beasiswa_mahasiswas.id_registrasi_mahasiswa', '=', 'riwayat_pendidikans.id_registrasi_mahasiswa')
                ->leftJoin('pembiayaans', 'beasiswa_mahasiswas.id_pembiayaan', '=', 'pembiayaans.id_pembiayaan')
                ->select('beasiswa_mahasiswas.*', 'riwayat_pendidikans.nama_program_studi as nama_program_studi', 'riwayat_pendidikans.id_periode_masuk as id_periode_masuk', 'pembiayaans.nama_pembiayaan as nama_pembiayaan');

        if ($searchValue) {
            $query = $query->where('beasiswa_mahasiswas.nim', 'like', '%' . $searchValue . '%')
                ->orWhere('beasiswa_mahasiswas.nama_mahasiswa', 'like', '%' . $searchValue . '%');
        }

        if ($request->has('prodi') && !empty($request->prodi)) {
            $filter = $request->prodi;
            $query->whereIn('id_prodi', $filter);
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

    public function beasiswa_template()
    {
        
    }

    public function beasiswa_upload(Request $request)
    {
        $data = $request->validate([
            'file' => 'required|mimes:xls,xlsx'
        ]);

        $file = $request->file('file');
        $import = Excel::import(new BeasiswaImport(), $file);

        return redirect()->back()->with('success', "Data successfully imported!");
    }
}
