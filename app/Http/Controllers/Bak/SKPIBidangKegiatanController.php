<?php

namespace App\Http\Controllers\Bak;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SKPIBidangKegiatan;

class SKPIBidangKegiatanController extends Controller
{
    public function index()
    {
        $data = SKPIBidangKegiatan::orderBy('nama_bidang', 'ASC')
            ->get();
        return view('bak.skpi.bidang.index', compact('data'));
    }

    public function create()
    {
        return view('bak.skpi.bidang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_bidang' => 'required',
            'nama_kegiatan' => 'required',
        ]);

        SKPIBidangKegiatan::create([
            'nama_bidang' => $request->nama_bidang,
            'nama_kegiatan' => $request->nama_kegiatan,
        ]);

        return redirect()->route('bak.skpi.bidang.index')
            ->with('success','Data berhasil ditambahkan');
    }

    public function edit($id)
    {
        $data = SKPIBidangKegiatan::findOrFail($id);
        return view('bak.skpi.bidang.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_bidang' => 'required',
            'nama_kegiatan' => 'required',
        ]);

        $data = SKPIBidangKegiatan::findOrFail($id);

        $data->update([
            'nama_bidang' => $request->nama_bidang,
            'nama_kegiatan' => $request->nama_kegiatan,
        ]);

        return redirect()->route('bak.skpi.bidang.index')
            ->with('success','Data berhasil diupdate');
    }

    public function destroy($id)
    {
        $data = SKPIBidangKegiatan::findOrFail($id);
        $data->delete();

        return redirect()->route('bak.skpi.bidang.index')
            ->with('success','Data berhasil dihapus');
    }
}