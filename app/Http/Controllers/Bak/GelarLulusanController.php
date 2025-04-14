<?php

namespace App\Http\Controllers\Bak;

use App\Http\Controllers\Controller;
use App\Models\ProgramStudi;
use App\Models\Referensi\GelarLulusan;
use Illuminate\Http\Request;

class GelarLulusanController extends Controller
{
    public function index()
    {
        $data = ProgramStudi::with(['gelar_lulusan', 'fakultas'])->where('status', 'A')->orderBy('kode_program_studi')->get();
        return view('bak.gelar-lulusan.index', [
            'data' => $data
        ]);
    }

    public function edit(ProgramStudi $prodi)
    {
        dd($prodi);

        $gelar = GelarLulusan::where('id_prodi', $prodi->id_prodi)->get();

        return view('bak.gelar-lulusan.edit', [
            'prodi' => $prodi,
            'gelar' => $gelar
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_prodi' => 'required|exists:program_studis,id_prodi',
            'gelar' => 'required',
            'gelar_panjang' => 'required',
        ]);

        try {
            GelarLulusan::updateOrCreate(['id_prodi' => $data['id_prodi']], [
                'gelar' => $data['gelar'],
                'gelar_panjang' => $data['gelar_panjang'],
            ]);
        } catch (\Throwable $th) {
            return redirect()->route('bak.gelar-lulusan')->with('error', 'Data gelar lulusan gagal disimpan!');
        }

        return redirect()->route('bak.gelar-lulusan')->with('success', 'Data gelar lulusan berhasil disimpan!');
    }
}
