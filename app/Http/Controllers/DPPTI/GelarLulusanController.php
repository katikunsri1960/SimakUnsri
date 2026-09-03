<?php

namespace App\Http\Controllers\DPPTI;

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

        return view('dppti.data-master.gelar-lulusan.index', compact('data'));

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
}
