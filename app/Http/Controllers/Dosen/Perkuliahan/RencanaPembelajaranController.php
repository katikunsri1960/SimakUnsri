<?php

namespace App\Http\Controllers\Dosen\Perkuliahan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RencanaPembelajaranController extends Controller
{
    public function rencana_pembelajaran()
    {
        return view('dosen.perkuliahan.rencana-pembelajaran.index');
    }
}
