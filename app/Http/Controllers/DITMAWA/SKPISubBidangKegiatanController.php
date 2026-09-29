<?php

namespace App\Http\Controllers\DITMAWA;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SKPISubBidangKegiatan;
use App\Models\SKPIBidangKegiatan;

class SKPISubBidangKegiatanController extends Controller
{
    public function index()
    {
        $data = SKPISubBidangKegiatan::with('bidang')
            ->orderBy('created_at', 'ASC')
            ->get();

        $bidang = SKPIBidangKegiatan::all();

        return view('ditmawa.skpi.sub-bidang.index', compact('data', 'bidang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'bidang_id' => 'required|exists:skpi_bidang_kegiatan,id',
            'nama_sub_bidang' => 'required|string|max:255',
        ]);

        try {
            SKPISubBidangKegiatan::create([
                'bidang_id' => $request->bidang_id,
                'nama_sub_bidang' => $request->nama_sub_bidang,
            ]);

            return redirect()->back()->with('success', 'Sub Bidang berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan Sub Bidang');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'bidang_id' => 'required|exists:skpi_bidang_kegiatan,id',
            'nama_sub_bidang' => 'required|string|max:255',
        ]);

        try {
            $data = SKPISubBidangKegiatan::findOrFail($id);
            $data->update([
                'bidang_id' => $request->bidang_id,
                'nama_sub_bidang' => $request->nama_sub_bidang,
            ]);

            return redirect()->back()->with('success', 'Sub Bidang berhasil diupdate');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal update Sub Bidang');
        }
    }

    public function destroy($id)
    {
        try {
            $data = SKPISubBidangKegiatan::findOrFail($id);
            $data->delete();

            return redirect()->back()->with('success', 'Sub Bidang berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus Sub Bidang');
        }
    }
}