<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\kehadiran_mahasiswa;
use Illuminate\Support\Facades\DB;

class ApiKehadiranMahasiswa extends Controller
{
    // Semua penelitian
    public function kehadiran_mahasiswa()
    {
        $kehadiran_mahasiswa = kehadiran_mahasiswa::select(
            'kode_mata_kuliah',
            'username',
            'nama_kelas',
            'nama_mk',
            'session_id',
            'session_date',
            'deskripsi_sesi',
            'id_kehadiran',
            'status_id',
            'status_mahasiswa'
        )->paginate(500);

        return response()->json([
            'kehadiran' => $kehadiran_mahasiswa
        ]);
    }
}
