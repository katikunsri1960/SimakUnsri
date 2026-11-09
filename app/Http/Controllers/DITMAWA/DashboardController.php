<?php

namespace App\Http\Controllers\DITMAWA;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use App\Models\Mahasiswa\RiwayatPendidikan;

class DashboardController extends Controller
{
    public function index()
    {
        return view('ditmawa.dashboard');
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

    public function get_mahasiswa(Request $request)
    {
        try {
            $search   = $request->get('q');
            $selected = $request->get('selected', []); // Array ID yang sudah dipilih

            $query = RiwayatPendidikan::with('prodi')
                ->whereNull('id_jenis_keluar');

            // Kecualikan mahasiswa yang sudah dipilih
            if (!empty($selected) && is_array($selected)) {
                $query->whereNotIn('id_registrasi_mahasiswa', $selected)
                    ->whereNotIn('id_mahasiswa', $selected);
            }

            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('nama_mahasiswa', 'like', "%{$search}%")
                    ->orWhere('nim', 'like', "%{$search}%");
                });
            }

            $mahasiswa = $query->orderBy('nama_mahasiswa', 'asc')->limit(25)->get();

            $results = [];
            foreach ($mahasiswa as $item) {
                $namaProdi = $item->nama_program_studi ?? optional($item->prodi)->nama_program_studi ?? '-';

                $results[] = [
                    'id'   => $item->id_registrasi_mahasiswa ?? $item->id_mahasiswa,
                    'text' => $item->nim . " - " . $item->nama_mahasiswa . " (" . $namaProdi . ")"
                ];
            }

            return response()->json(['results' => $results]);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
