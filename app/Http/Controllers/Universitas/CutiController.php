<?php

namespace App\Http\Controllers\Universitas;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa\PengajuanCuti;
use App\Models\Semester;
use Illuminate\Http\Request;

class CutiController extends Controller
{
    public function index(Request $request)
    {
        $db = new PengajuanCuti;

        $data = $db->with(['riwayat', 'prodi']);


        if ($request->has('semester')) {
            $data = $data->where('semester', $request->semester);
        }

        $data = $data->get();
        $semester = Semester::orderBy('id_semester', 'desc')->get();

        return view('universitas.cuti.index', [
            'semester' => $semester,
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_registrasi_mahasiswa' => 'required|exists:riwayat_pendidikans,id_registrasi_mahasiswa',
            'id_semester' => 'required|exists:semesters,id_semester',

        ]);
    }
}
