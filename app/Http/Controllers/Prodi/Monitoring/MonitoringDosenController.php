<?php

namespace App\Http\Controllers\Prodi\Monitoring;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MonitoringDosenController extends Controller
{
    public function monitoring_nilai()
    {
        return view('prodi.monitoring.entry-nilai.index');
    }

    public function monitoring_pengajaran()
    {
        return view('prodi.monitoring.pengajaran-dosen.index');
    }
}
