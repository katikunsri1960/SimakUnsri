<?php

namespace App\Http\Controllers\Bak;

use App\Http\Controllers\Controller;
use App\Models\BeasiswaMahasiswa;
use Illuminate\Http\Request;

class BeasiswaController extends Controller
{
    public function index(Request $request)
    {
        $data = BeasiswaMahasiswa::all();

        return view('bak.beasiswa.index', [
            'data' => $data
        ]);
    }
}
