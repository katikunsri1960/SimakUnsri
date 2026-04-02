<?php

namespace App\Http\Controllers\Prodi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CPLKurikulum;
use App\Models\Perkuliahan\ListKurikulum;

class CPLKurikulumController extends Controller
{
    // public function list_kurikulum()
    // {
    //     $data = ListKurikulum::where('id_prodi', auth()->user()->fk_id)->where('is_active', 1)->get();
    //     return view('prodi.data-master.capaian-pembelajaran.index',
    //     [
    //         'data' => $data
    //     ]);
    // }

    /*
    |--------------------------------------------------------------------------
    | GET ALL DATA
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $list_kurikulum = ListKurikulum::where('id_prodi', auth()->user()->fk_id)->where('is_active', 1)->get();

        $data = CPLKurikulum::with('kurikulum')
            ->whereHas('kurikulum', function($q) {
                $q->where('id_prodi', auth()->user()->fk_id);
            })
            ->get();

        return view('prodi.data-master.capaian-pembelajaran.index',
        [
            'data' => $data,
            'list_kurikulum' => $list_kurikulum
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | STORE DATA
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'id_kurikulum' => 'required',
            'nama_cpl' => 'required|string'
        ]);

        try {
            $data = CPLKurikulum::create([
                'id_kurikulum' => $request->id_kurikulum,
                'nama_cpl' => $request->nama_cpl
            ]);

            return redirect()->back()->with('success', 'Data Berhasil di Tambahkan');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Data Gagal di Tambahkan');
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

            return redirect()->back()->with('success', 'Data Berhasil di Diubah');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Data Gagal di Diubah');
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

            return redirect()->back()->with('success', 'Data Berhasil di Dihapus');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Data Gagal di Dihapus');
        }
    }
}