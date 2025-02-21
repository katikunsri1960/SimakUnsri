<?php

namespace App\Http\Controllers\Fakultas\LainLain;

use Ramsey\Uuid\Uuid;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use App\Models\SemesterAktif;
use App\Http\Controllers\Controller;
use App\Models\Mahasiswa\PengajuanCuti;
use App\Models\Mahasiswa\RiwayatPendidikan;

class CutiController extends Controller
{
    public function index()
    {
        // dd($semester_aktif->id_semester);
        $fak_id = auth()->user()->fk_id;

        $id_prodi_fak = ProgramStudi::where('fakultas_id', auth()->user()->fk_id)
                    ->orderBy('id_jenjang_pendidikan')
                    ->orderBy('nama_program_studi')
                    ->pluck('id_prodi');
        
        $data = PengajuanCuti::with(['prodi', 'riwayat'])->whereIn('id_prodi', $id_prodi_fak)->get();
        // dd($data);

        return view('fakultas.lain-lain.pengajuan-cuti.index', ['data' => $data]);
    }

    public function cuti_approve(PengajuanCuti $cuti)
    {
        // dd($cuti);
        $store = $cuti->update([
            'approved' => 1,
            'alasan_pembatalan' => NULL
        ]);

        return redirect()->back()->with('success', 'Pengajuan Cuti berhasil disimpan');
    }

    public function pembatalan_cuti(Request $request, $cuti)
    {
        PengajuanCuti::where('id_cuti',$cuti)->update([
            'approved' => 3,
            'alasan_pembatalan' => $request->alasan_pembatalan
        ]);

        return redirect()->back()->with('success', 'Pengajuan Cuti berhasil dibatalkan');
    }
}
