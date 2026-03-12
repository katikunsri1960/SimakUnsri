<?php

namespace App\Http\Controllers\Bak;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SkpiJenisKegiatan;
use App\Models\SkpiBidangKegiatan;

class SKPIJenisKegiatanController extends Controller
{
    public function index()
    {
        $data = SKPIJenisKegiatan::with('bidang')
            ->orderBy('created_at', 'ASC')
            ->get();
        return view('bak.skpi.jenis.index', compact('data'));
    }

    public function create()
    {
        $bidang = SKPIBidangKegiatan::all();
        return view('bak.skpi.jenis.create', compact('bidang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'bidang_id' => 'required',
            'nama_jenis' => 'required',
            'kriteria' => 'required',
            'skor' => 'required|numeric',
        ]);

        SKPIJenisKegiatan::create([
            'bidang_id' => $request->bidang_id,
            'nama_jenis' => $request->nama_jenis,
            'kriteria' => $request->kriteria,
            'skor' => $request->skor,
        ]);

        return redirect()->route('bak.skpi-jenis.index')
            ->with('success','Data berhasil ditambahkan');
    }

    public function edit($id)
    {
        $data = SKPIJenisKegiatan::findOrFail($id);
        $bidang = SKPIBidangKegiatan::all();

        return view('skpi_jenis.edit', compact('data','bidang'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'bidang_id' => 'required',
            'nama_jenis' => 'required',
            'kriteria' => 'required',
            'skor' => 'required|numeric',
        ]);

        $data = SKPIJenisKegiatan::findOrFail($id);

        $data->update([
            'bidang_id' => $request->bidang_id,
            'nama_jenis' => $request->nama_jenis,
            'kriteria' => $request->kriteria,
            'skor' => $request->skor,
        ]);

        return redirect()->route('bak.skpi-jenis.index')
            ->with('success','Data berhasil diupdate');
    }

    public function destroy($id)
    {
        $data = SKPIJenisKegiatan::findOrFail($id);
        $data->delete();

        return redirect()->route('bak.skpi-jenis.index')
            ->with('success','Data berhasil dihapus');
    }
}