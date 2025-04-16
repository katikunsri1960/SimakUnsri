<?php

namespace App\Http\Controllers\Prodi\Report;

use App\Http\Controllers\Controller;

class ReportKemahasiswaanController extends Controller
{
    public function index()
    {
        return view('prodi.report.kemahasiswaan.index');
    }
}
