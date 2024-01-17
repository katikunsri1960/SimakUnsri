<?php

namespace App\Http\Controllers\Prodi\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportKemahasiswaanController extends Controller
{
    public function index()
    {
        return view('prodi.report.kemahasiswaan.index');
    }
}
