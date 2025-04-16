<?php

namespace App\Http\Controllers\Prodi\Report;

use App\Http\Controllers\Controller;

class ReportPerkuliahanMahasiswaController extends Controller
{
    public function index()
    {
        return view('prodi.report.kuliah.index');
    }
}
