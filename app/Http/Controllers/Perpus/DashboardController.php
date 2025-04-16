<?php

namespace App\Http\Controllers\Perpus;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('perpus.dashboard');
    }
}
