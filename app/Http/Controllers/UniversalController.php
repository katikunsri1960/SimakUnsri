<?php

namespace App\Http\Controllers;

use App\Models\ProgramStudi;
use Illuminate\Http\Request;

class UniversalController extends Controller
{
    public function get_prodi_by_fakultas(Request $request)
    {
        $fakultas_id = $request->input('fakultas_id');
        $prodi = ProgramStudi::select('id_prodi','nama_program_studi', 'kode_program_studi', 'nama_jenjang_pendidikan')
                ->where('fakultas_id', $fakultas_id)->get();

        if ($prodi->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No programs found for this faculty'], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Programs retrieved successfully',
            'data' => $prodi
        ]);
    }
}
