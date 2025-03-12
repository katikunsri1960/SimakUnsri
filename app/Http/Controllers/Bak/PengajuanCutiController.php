<?php

namespace App\Http\Controllers\Bak;

use App\Models\Semester;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use App\Models\SemesterAktif;
use App\Http\Controllers\Controller;
use App\Models\Mahasiswa\PengajuanCuti;

class PengajuanCutiController extends Controller
{
    public function index(Request $request)
    {
        $db = new PengajuanCuti;

        $request->validate([
            'semester_view' => 'nullable|exists:semesters,id_semester',
        ]);

        $data = $db->with(['riwayat', 'prodi']);

        $pilihan_semester = Semester::select('id_semester', 'nama_semester')->orderBy('id_semester', 'desc')->get();
        $semester_view = $request->semester_view ?? SemesterAktif::select('id_semester')->first()->id_semester;

        $prodi = ProgramStudi::all();

        $data = $data->where('id_semester', $semester_view)
                ->get();

        return view('bak.pengajuan-cuti.index',[
            'data' => $data,
            'pilihan_semester' => $pilihan_semester,
            'semester_view' => $semester_view,
        ]);
    }

    public function cuti_approve(PengajuanCuti $cuti)
    {
        if($cuti->approved < 1){
            return redirect()->back()->with('error', 'Pengajuan Cuti belum disetujui Fakultas');
        }
        // dd($cuti);
        $store = $cuti->update([
            'approved' => 2,
            'alasan_pembatalan' => NULL
        ]);

        return redirect()->back()->with('success', 'Pengajuan Cuti berhasil disimpan');
    }

    public function pembatalan_cuti(Request $request, $cuti)
    {
        PengajuanCuti::where('id_cuti',$cuti)->update([
            'approved' => 4,
            'alasan_pembatalan' => $request->alasan_pembatalan
        ]);

        return redirect()->back()->with('success', 'Pengajuan Cuti berhasil dibatalkan');
    }
}
