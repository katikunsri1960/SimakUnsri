<?php

namespace App\Http\Controllers\Bak;

use App\Http\Controllers\Controller;
use App\Models\Referensi\PredikatKelulusan;
use Illuminate\Http\Request;

class DataMasterController extends Controller
{
    public function predikat()
    {
        $data = PredikatKelulusan::all();
        return view('bak.data-master.predikat.index', [
            'data' => $data,
        ]);
    }

    public function predikat_store(Request $request)
    {
        $data = $request->validate([
            'indonesia' => 'required',
            'inggris' => 'required',
        ]);

        PredikatKelulusan::create($data);

        return redirect()->route('bak.data-master.predikat')->with('success', 'Data berhasil disimpan');
    }

    public function predikat_update(Request $request, PredikatKelulusan $predikat)
    {
        $data = $request->validate([
            'indonesia' => 'required',
            'inggris' => 'required',
        ]);

        $predikat->update($data);

        return redirect()->route('bak.data-master.predikat')->with('success', 'Data berhasil diupdate');
    }

    public function predikat_delete(PredikatKelulusan $predikat)
    {
        $predikat->delete();

        return redirect()->route('bak.data-master.predikat')->with('success', 'Data berhasil dihapus');
    }
}
