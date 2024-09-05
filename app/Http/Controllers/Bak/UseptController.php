<?php

namespace App\Http\Controllers\Bak;

use App\Http\Controllers\Controller;
use App\Models\Perkuliahan\ListKurikulum;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;

class UseptController extends Controller
{
    public function index(Request $request)
    {
        $data = ListKurikulum::with(['prodi'])->where('is_active', 1);

        if ($request->has('id_prodi') && $request->id_prodi != '') {
            $id_prodi = $request->id_prodi;
            $data = $data->whereIn('id_prodi', $id_prodi);
        }

        $data = $data->orderBy('nilai_usept')->orderBy('id_prodi')->get();

        $prodi = ProgramStudi::where('status', 'A')->get();

        return view('bak.usept.index', [
            'data' => $data,
            'prodi' => $prodi,
        ]);
    }

    public function store(ListKurikulum $kurikulum, Request $request)
    {
        $data = $request->validate([
            'nilai_usept' => 'required|numeric',
        ]);

        $kurikulum->update([
            'nilai_usept' => $data['nilai_usept'],
        ]);

        return redirect()->back()->with('success', 'Data berhasil disimpan');
    }
}
