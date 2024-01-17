<?php

namespace App\Http\Controllers\Prodi\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportMahasiswaAktifController extends Controller
{
    public function index()
    {
        return view('prodi.report.mahasiswa-aktif.index');
    }
}
