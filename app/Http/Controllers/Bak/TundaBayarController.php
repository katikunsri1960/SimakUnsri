<?php

namespace App\Http\Controllers\Bak;

use App\Http\Controllers\Controller;
use App\Models\PenundaanBayar;
use App\Models\Semester;
use App\Models\SemesterAktif;
use Illuminate\Http\Request;

class TundaBayarController extends Controller
{
    public function index(Request $request)
    {
        $semester_aktif = SemesterAktif::first()->id_semester;
        $semester = Semester::select('id_semester', 'nama_semester')
            ->where('id_semester', '<=', $semester_aktif)
            ->whereNot('semester', 3)
            ->orderBy('id_semester', 'desc')->get();

        $db = new PenundaanBayar;

        $data = $db->with(['riwayat.prodi'])->filter($request)->get();

        $status = PenundaanBayar::STATUS;
        $total = $data->count();
        // count data per status dari $data
        $count = [];
        foreach ($status as $key => $value) {
            $count[$key]['status'] = $value['status'];
            $count[$key]['jumlah'] = $data->where('status', $key)->count();
            $count[$key]['persen'] = $total > 0 ? $count[$key]['jumlah'] / $total * 100 : 0;
            $count[$key]['class'] = $value['class'];
        }

        return view('bak.tunda-bayar.index', [
            'data' => $data,
            'semester' => $semester,
            'semester_aktif' => $semester_aktif,
            'count' => $count,
        ]);
    }

    public function approve(PenundaanBayar $tunda_bayar)
    {
        if ($tunda_bayar->status != 3) {
            return redirect()->back()->with('error', 'Data tidak dapat disetujui');
        }

        $tunda_bayar->update(['status' => 4]);

        return redirect()->back()->with('success', 'Data berhasil disetujui');
    }

    public function decline(PenundaanBayar $tunda_bayar, Request $request)
    {
        $data = $request->validate([
            'alasan_pembatalan' => 'required',
        ]);

        if ($tunda_bayar->status != 3) {
            return redirect()->back()->with('error', 'Data tidak dapat ditolak');
        }

        $tunda_bayar->update([
            'alasan_pembatalan' => $data['alasan_pembatalan'],
            'status' => 5,
        ]);

        return redirect()->back()->with('success', 'Data berhasil ditolak');
    }
}
