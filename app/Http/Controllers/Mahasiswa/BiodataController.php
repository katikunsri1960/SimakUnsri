<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BiodataController extends Controller
{
    public function index()
    {
        $biodata = DB::table('biodata_mahasiswa')
            ->select('*')
            // ->order('ni', 'desc')
            ->where('nik', '1608031902960001')
            ->first();
        return view('mahasiswa.biodata.index', compact('biodata'));
    }
}
