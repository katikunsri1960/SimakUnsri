<?php

namespace App\Http\Controllers\Universitas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FeederUploadController extends Controller
{
    public function index()
    {
        return view('universitas.feeder-upload.index');
    }

    public function data(Request $request)
    {
        
    }
}
