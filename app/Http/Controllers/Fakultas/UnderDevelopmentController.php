<?php

namespace App\Http\Controllers\Fakultas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UnderDevelopmentController extends Controller
{
    public function index(Request $request)
    {
        return view('fakultas.devop');
    }
}
