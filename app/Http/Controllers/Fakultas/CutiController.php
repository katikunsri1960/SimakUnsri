<?php

namespace App\Http\Controllers\Fakultas;

use Ramsey\Uuid\Uuid;
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
        $id_reg = auth()->user()->fk_id;
        
        $data = PengajuanCuti::where('id_registrasi_mahasiswa', $id_reg)->where('approved',1)->get();
        // dd($data_mahasiswa->biodata->id_mahasiswa);

        return view('fakultas.pengajuan-cuti.index', ['data' => $data]);
    }

    public function tambah()
    {
        // dd($semester_aktif->id_semester);
        $id_reg = auth()->user()->fk_id;
        $data = RiwayatPendidikan::where('id_registrasi_mahasiswa', $id_reg)->first();

        return view('fakultas.pengajuan-cuti.store', ['data' => $data]);
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
