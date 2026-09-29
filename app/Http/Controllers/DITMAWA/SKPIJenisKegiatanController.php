<?php

namespace App\Http\Controllers\DITMAWA;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SKPIJenisKegiatan;
use App\Models\SKPIBidangKegiatan;
use App\Models\SKPISubBidangKegiatan; // ✅ Model Sub Bidang

class SKPIJenisKegiatanController extends Controller
{
    public function index()
    {
        $data = SKPIJenisKegiatan::with(['bidang', 'subBidang'])
            ->orderBy('created_at', 'ASC')
            ->get();

        $bidang = SKPIBidangKegiatan::all();
        // Ambil data sub bidang yang bidang_id-nya = 4 (atau semua sub bidang)
        $subBidang = SKPISubBidangKegiatan::where('bidang_id', 4)->get();

        return view('ditmawa.skpi.jenis.index', compact('data', 'bidang', 'subBidang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'bidang_id' => 'required|exists:skpi_bidang_kegiatan,id',
            // Wajib diisi jika bidang_id bernilai 4
            'sub_bidang_id' => 'required_if:bidang_id,4|nullable|exists:skpi_sub_bidang_kegiatan,id',
            'nama_jenis' => 'required|string|max:255',
            'kriteria' => 'required',
            'skor' => 'required|numeric|min:0',
        ], [
            'sub_bidang_id.required_if' => 'Sub Bidang wajib dipilih untuk bidang ini!'
        ]);

        try {
            SKPIJenisKegiatan::create([
                'bidang_id' => $request->bidang_id,
                'sub_bidang_id' => $request->bidang_id == 4 ? $request->sub_bidang_id : null,
                'nama_jenis' => $request->nama_jenis,
                'kriteria' => $request->kriteria,
                'skor' => $request->skor,
            ]);

            return redirect()->back()->with('success', 'Data berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan data');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'bidang_id' => 'required|exists:skpi_bidang_kegiatan,id',
            // Wajib diisi jika bidang_id bernilai 4
            'sub_bidang_id' => 'required_if:bidang_id,4|nullable|exists:skpi_sub_bidang_kegiatan,id',
            'nama_jenis' => 'required|string|max:255',
            'kriteria' => 'required',
            'skor' => 'required|numeric|min:0',
        ], [
            'sub_bidang_id.required_if' => 'Sub Bidang wajib dipilih untuk bidang ini!'
        ]);

        try {
            $data = SKPIJenisKegiatan::findOrFail($id);

            $data->update([
                'bidang_id' => $request->bidang_id,
                'sub_bidang_id' => $request->bidang_id == 4 ? $request->sub_bidang_id : null,
                'nama_jenis' => $request->nama_jenis,
                'kriteria' => $request->kriteria,
                'skor' => $request->skor,
            ]);

            return redirect()->back()->with('success', 'Data berhasil diupdate');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal update data');
        }
    }

    public function destroy($id)
    {
        try {
            $data = SKPIJenisKegiatan::findOrFail($id);
            $data->delete();

            return redirect()->back()->with('success', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal hapus data');
        }
    }
}