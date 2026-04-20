<?php

namespace App\Http\Controllers\Prodi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CPLKurikulum;
use App\Models\Perkuliahan\ListKurikulum;
use Illuminate\Support\Facades\DB;

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
            ->orderBy('id_kurikulum', 'asc')
            ->orderBy('kode_cpl', 'asc')
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
        $request->validate([
            'id_kurikulum' => 'required',
            'nama_cpl' => 'required|string'
        ]);

        try {

            // 🔥 Ambil nomor terakhir berdasarkan kurikulum
            $last = CPLKurikulum::where('id_kurikulum', $request->id_kurikulum)
                ->orderBy('kode_cpl', 'desc')
                ->first();

            // 🔢 Ambil angka terakhir
            if ($last && $last->kode_cpl) {
                $lastNumber = (int) substr($last->kode_cpl, -2);
                $nextNumber = $lastNumber + 1;
            } else {
                $nextNumber = 1;
            }

            // 🎯 Format kode (CPL-01, CPL-02, dst)
            $kode_cpl = 'CPL-' . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);

            // 💾 Simpan
            CPLKurikulum::create([
                'id_kurikulum' => $request->id_kurikulum,
                'kode_cpl' => $kode_cpl,
                'nama_cpl' => $request->nama_cpl
            ]);

            return redirect()->back()->with('success', 'Data Berhasil di Tambahkan');

        } 
        catch (\Exception $e) {
            return redirect()->back()->with('error', 'Data Gagal di Tambahkan');
        }
        //         catch (\Exception $e) {
        //     DB::rollBack();
        //     dd($e->getMessage(), $e->getLine(), $e->getFile());
        // }
    }

    public function getLastKode(Request $request)
    {
        $id = $request->id_kurikulum;

        if (!$id) {
            return response()->json([
                'status' => 'error',
                'message' => 'ID Kurikulum tidak ada'
            ]);
        }

        $last = CPLKurikulum::where('id_kurikulum', $id)
            ->whereNotNull('kode_cpl')
            ->selectRaw("MAX(CAST(SUBSTRING(kode_cpl, 5) AS UNSIGNED)) as max_no")
            ->value('max_no');

        $next = $last ? $last + 1 : 1;

        return response()->json([
            'status' => 'success',
            'kode' => 'CPL-' . str_pad($next, 2, '0', STR_PAD_LEFT)
        ]);
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
            'id_kurikulum' => 'required',
            'nama_cpl' => 'required|string'
        ]);

        $data = CPLKurikulum::find($id);

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        try {

            // ❗ Jika kurikulum berubah → generate ulang kode
            if ($data->id_kurikulum != $request->id_kurikulum) {

                $last = CPLKurikulum::where('id_kurikulum', $request->id_kurikulum)
                    ->whereNotNull('kode_cpl')
                    ->selectRaw("MAX(CAST(SUBSTRING(kode_cpl, 5) AS UNSIGNED)) as max_no")
                    ->value('max_no');

                $next = $last ? $last + 1 : 1;

                $data->kode_cpl = 'CPL-' . str_pad($next, 2, '0', STR_PAD_LEFT);
            }

            // update data
            $data->update([
                'id_kurikulum' => $request->id_kurikulum,
                'nama_cpl' => $request->nama_cpl
            ]);

            return redirect()->back()->with('success', 'Data berhasil diupdate');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal update data');
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