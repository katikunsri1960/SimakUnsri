<?php

namespace App\Http\Controllers\Prodi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CPLKurikulum;

class CPLKurikulumController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | GET ALL DATA
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $query = CPLKurikulum::query();

        // filter id_kurikulum
        if ($request->id_kurikulum) {
            $query->where('id_kurikulum', $request->id_kurikulum);
        }

        // search nama_cpl
        if ($request->search) {
            $query->where('nama_cpl', 'like', '%' . $request->search . '%');
        }

        $data = $query->orderBy('id', 'DESC')->get();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | STORE DATA
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $request->validate([
            'id_kurikulum' => 'required|integer',
            'nama_cpl' => 'required|string'
        ]);

        try {
            $data = CPLKurikulum::create([
                'id_kurikulum' => $request->id_kurikulum,
                'nama_cpl' => $request->nama_cpl
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil disimpan',
                'data' => $data
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan data',
                'error' => $e->getMessage()
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW DETAIL
    |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $data = CPLKurikulum::find($id);

        if (!$data) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE DATA
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_kurikulum' => 'required|integer',
            'nama_cpl' => 'required|string'
        ]);

        $data = CPLKurikulum::find($id);

        if (!$data) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ]);
        }

        try {
            $data->update([
                'id_kurikulum' => $request->id_kurikulum,
                'nama_cpl' => $request->nama_cpl
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil diupdate',
                'data' => $data
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal update data',
                'error' => $e->getMessage()
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE DATA
    |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        $data = CPLKurikulum::find($id);

        if (!$data) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ]);
        }

        try {
            $data->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal hapus data',
                'error' => $e->getMessage()
            ]);
        }
    }
}