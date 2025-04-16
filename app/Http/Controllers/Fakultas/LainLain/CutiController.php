<?php

namespace App\Http\Controllers\Fakultas\LainLain;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa\PengajuanCuti;
use App\Models\ProgramStudi;
use App\Models\Semester;
use App\Models\SemesterAktif;
use Illuminate\Http\Request;

class CutiController extends Controller
{
    public function index(Request $request)
    {
        $db = new PengajuanCuti;

        $request->validate([
            'semester_view' => 'nullable|exists:semesters,id_semester',
        ]);

        $pilihan_semester = Semester::select('id_semester', 'nama_semester')->orderBy('id_semester', 'desc')->get();
        $semester_view = $request->semester_view ?? SemesterAktif::select('id_semester')->first()->id_semester;

        // dd($semester_aktif->id_semester);
        $fak_id = auth()->user()->fk_id;

        $id_prodi_fak = ProgramStudi::where('fakultas_id', auth()->user()->fk_id)
            ->orderBy('id_jenjang_pendidikan')
            ->orderBy('nama_program_studi')
            ->pluck('id_prodi');

        $data = PengajuanCuti::with(['prodi', 'riwayat'])
            ->whereIn('id_prodi', $id_prodi_fak)
            ->where('id_semester', $semester_view)
            ->get();
        // dd($data);

        return view('fakultas.lain-lain.pengajuan-cuti.index', [
            'data' => $data,
            'pilihan_semester' => $pilihan_semester,
            'semester_view' => $semester_view,
        ]);
    }

    public function cuti_approve(PengajuanCuti $cuti)
    {
        // dd($cuti);
        $store = $cuti->update([
            'approved' => 1,
            'alasan_pembatalan' => null,
        ]);

        return redirect()->back()->with('success', 'Pengajuan Cuti berhasil disimpan');
    }

    public function pembatalan_cuti(Request $request, $cuti)
    {
        PengajuanCuti::where('id_cuti', $cuti)->update([
            'approved' => 3,
            'alasan_pembatalan' => $request->alasan_pembatalan,
        ]);

        return redirect()->back()->with('success', 'Pengajuan Cuti berhasil dibatalkan');
    }
}
