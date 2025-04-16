<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Dosen\BiodataDosen;

class BiodataDosenController extends Controller
{
    public function biodata_dosen()
    {
        $db = new BiodataDosen;

        $data = $db->data_dosen(auth()->user()->fk_id);

        return view('dosen.biodata-dosen', [
            'data' => $data,
        ]);
    }
}
