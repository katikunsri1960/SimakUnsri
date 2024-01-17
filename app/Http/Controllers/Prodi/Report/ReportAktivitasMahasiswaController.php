<?php

namespace App\Http\Controllers\Prodi\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportAktivitasMahasiswaController extends Controller
{
    public function index()
    {
        return view('prodi.report.aktivitas-mahasiswa.index');
    }
}
