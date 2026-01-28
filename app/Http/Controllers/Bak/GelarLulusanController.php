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
        $data = GelarLulusan::with(['prodi', 'prodi.fakultas'])
                ->whereHas('prodi', function ($q) {
                    $q->where('status', 'A');
                })
                ->join('program_studis', 'program_studis.id_prodi', '=', 'gelar_lulusans.id_prodi')
                ->orderBy('program_studis.kode_program_studi')
                ->select('gelar_lulusans.*')
                ->get();

        return view('bak.gelar-lulusan.index', compact('data'));

    }

    public function edit($id_gelar)
    {
        // dd($prodi);

        $gelar = GelarLulusan::where('id', $id_gelar)->first();
        $prodi = ProgramStudi::where('id_prodi', $gelar->id_prodi)->first();

        return view('bak.gelar-lulusan.edit', [
            'prodi' => $prodi,
            'gelar' => $gelar
        ]);
    }

    public function get_prodi(Request $request)
    {
        $search = $request->get('q');

        $query = ProgramStudi::orderby('nama_jenjang_pendidikan', 'asc')->orderby('nama_program_studi', 'asc');
        if ($search) {
            $query->where('nama_jenjang_pendidikan', 'like', "%{$search}%")
                  ->orWhere('nama_program_studi', 'like', "%{$search}%");
        }

        $data = $query->get();

        return response()->json($data);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'id_gelar' => 'required',
            'id_prodi' => 'required|exists:program_studis,id_prodi',
            'gelar' => 'required',
            'gelar_panjang' => 'required',
        ]);

        try {
            GelarLulusan::updateOrCreate(['id' => $data['id_gelar'], 'id_prodi' => $data['id_prodi']], [
                'gelar' => $data['gelar'],
                'gelar_panjang' => $data['gelar_panjang'],
            ]);
        } catch (\Throwable $th) {
            return redirect()->route('bak.gelar-lulusan')->with('error', 'Data gelar lulusan gagal disimpan!');
        }

        return redirect()->route('bak.gelar-lulusan')->with('success', 'Data gelar lulusan berhasil disimpan!');
    }

    public function store(Request $request)
    {
        // dd($request);
        $data = $request->validate([
            'id_prodi' => 'required|exists:program_studis,id_prodi',
            'gelar' => 'required',
            'gelar_panjang' => 'required',
        ]);

        try {
            GelarLulusan::create([
                'id_prodi' => $data['id_prodi'],
                'gelar' => $data['gelar'],
                'gelar_panjang' => $data['gelar_panjang'],
            ]);
        } catch (\Throwable $th) {
            return redirect()->route('bak.gelar-lulusan')->with('error', 'Data gelar lulusan gagal disimpan!');
        }

        return redirect()->route('bak.gelar-lulusan')->with('success', 'Data gelar lulusan berhasil disimpan!');
    }
}
