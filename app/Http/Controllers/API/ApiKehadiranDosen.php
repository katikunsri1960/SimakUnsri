<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\kehadiran_dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiKehadiranDosen extends Controller
{
    // Semua Kehadiran
    public function kehadiran_dosen()
    {
        $kehadiran_dosen = kehadiran_dosen::select(
            'kode_mata_kuliah',
            'nama_kelas',
            'id_kelas_kuliah',
            'nama_mk',
            'session_id',
            'session_date',
            'timemodified',
            'lasttaken',
            'deskripsi_sesi',
            'id_kehadiran',
        )->paginate(500);

        return response()->json([
            'kehadiran' => $kehadiran_dosen
        ]);
    }
}
