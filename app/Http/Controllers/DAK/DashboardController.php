<?php

namespace App\Http\Controllers\DAK;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use App\Models\Mahasiswa\RiwayatPendidikan;

class DashboardController extends Controller
{
    public function index()
    {
        return view('bak.dashboard');
    }

    public function check_sync(Request $request)
    {
        $id_batch = $request->id_batch;
        $batching = Bus::findBatch($id_batch);

        return [
            'total' => $batching->totalJobs,
            'job_processed' => $batching->processedJobs(),
            'job_pending' => $batching->pendingJobs,
            'progress' => $batching->progress(),
        ];
    }

    public function search(Request $request)
    {
        $data = RiwayatPendidikan::select('id_registrasi_mahasiswa', 'nim', 'nama_mahasiswa', 'nama_program_studi')
                ->where('nim', 'like', '%'.$request->q.'%')
                ->orWhere('nama_mahasiswa', 'like', '%'.$request->q.'%')
                ->get();

        return response()->json($data);
    }
}
