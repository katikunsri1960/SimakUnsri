<?php

namespace App\Http\Controllers\Fakultas;

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
        
        $data = PengajuanCuti::with(['prodi', 'riwayat_pendidikan'])->whereIn('id_prodi', $id_prodi_fak)->get();
        // dd($data);

        return view('fakultas.pengajuan-cuti.index', ['data' => $data]);
    }

    public function cuti_approve(PengajuanCuti $cuti)
    {
        // $dosen = auth()->user()->fk_id;

        // $pembimbing_ke = PengajuanCuti::where('id_aktivitas', $cuti->id_cuti)
        //                     ->where('id_dosen', $dosen)
        //                     ->first()->pembimbing_ke;

        // if ($cuti->id_dosen != $dosen && $pembimbing_ke != 1) {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'Anda tidak memiliki akses untuk menyetujui asistensi ini'
        //     ]);
        // }

        $store = $cuti->update([
            'approved' => 1
        ]);

        if ($store) {
            return response()->json([
                'status' => 'success',
                'message' => 'Pengajuan Cuti berhasil disetujui!'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menyetujui Pengajuan Cuti'
            ]);
        }
    }

    public function store(Request $request)
    {
        //Define variable
        $id_reg = auth()->user()->fk_id;
        $semester_aktif = SemesterAktif::first();

        $riwayat_pendidikan = RiwayatPendidikan::select('*')
                    ->where('id_registrasi_mahasiswa', $id_reg)
                    ->first();

        // Cek apakah sudah ada pengajuan cuti yang sedang diproses
        $existingCuti = PengajuanCuti::where('id_registrasi_mahasiswa', $id_reg)
        ->where('id_semester', $semester_aktif->id_semester)
        ->first();
        // dd($existingCuti->approved);

        // Jika sudah ada pengajuan cuti yang sedang diproses, tampilkan pesan error
        if ($existingCuti->approved == 0) {
            return redirect()->back()->with('error', 'Anda sudah memiliki pengajuan cuti yang sedang diproses. Tunggu persetujuan atau batalkan pengajuan sebelum membuat pengajuan baru.');
        }
        elseif ($existingCuti->approved == 1) {
            return redirect()->back()->with('error', 'Anda sudah memiliki pengajuan cuti yang sudah disetujui.');
        }

        //Validate request data
        $request->validate([
            'alasan_cuti' => 'required',
            'file_pendukung' => 'required|file|mimes:pdf|max:2048',
        ]);

        PengajuanCuti::where('id_registrasi_mahasiswa', $id_reg)->where('id_semester', $semester_aktif->id_semester)->update([
            'approved' => 2,
        ]);
        
        // Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('fakultas.pengajuan-cuti.index')->with('success', 'Data Berhasil di Tambahkan');
    }
}
