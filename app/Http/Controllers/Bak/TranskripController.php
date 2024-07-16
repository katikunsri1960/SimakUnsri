<?php

namespace App\Http\Controllers\Bak;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TranskripController extends Controller
{
    public function index()
    {
        return view('bak.transkrip-nilai');
    }
}
