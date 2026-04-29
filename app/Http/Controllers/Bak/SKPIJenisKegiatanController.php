<?php

namespace App\Http\Controllers\Bak;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SKPIJenisKegiatan;
use App\Models\SKPIBidangKegiatan;

class SKPIJenisKegiatanController extends Controller
{
    public function index()
    {
        $data = SKPIJenisKegiatan::with('bidang')
            ->orderBy('created_at', 'ASC')
            ->get();

        $bidang = SKPIBidangKegiatan::all(); // ✅ WAJIB untuk modal

        // dd($data, $bidang);

        return view('bak.skpi.jenis.index', compact('data','bidang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'bidang_id'   => 'required|exists:skpi_bidang_kegiatan,id',
            'nama_jenis'  => 'required|string|max:255',
            'kriteria'    => 'required',
            'skor'        => 'required|numeric|min:0',
        ]);

        try {
            SKPIJenisKegiatan::create($request->all());

            return redirect()->back()
                ->with('success','Data berhasil ditambahkan');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error','Gagal menambahkan data');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'bidang_id'   => 'required|exists:skpi_bidang_kegiatan,id',
            'nama_jenis'  => 'required|string|max:255',
            'kriteria'    => 'required',
            'skor'        => 'required|numeric|min:0',
        ]);

        try {
            $data = SKPIJenisKegiatan::findOrFail($id);

            $data->update($request->all());

            return redirect()->back()
                ->with('success','Data berhasil diupdate');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error','Gagal update data');
        }
    }

    public function destroy($id)
    {
        // dd($id);
        try {
            $data = SKPIJenisKegiatan::findOrFail($id);
            $data->delete();

            return redirect()->back()
                ->with('success','Data berhasil dihapus');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error','Gagal hapus data');
        }
    }
}