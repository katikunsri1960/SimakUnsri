<?php

namespace App\Http\Controllers\Mahasiswa\Cuti;

use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use App\Models\SemesterAktif;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\Mahasiswa\PengajuanCuti;
use App\Models\Referensi\JenisPrestasi;
use App\Models\Referensi\TingkatPrestasi;
use App\Models\Mahasiswa\PrestasiMahasiswa;
use App\Models\Mahasiswa\RiwayatPendidikan;

class CutiController extends Controller
{
    // public function index()
    // {
    //     return view('mahasiswa.pengajuan-cuti');
    // }

    public function index()
    {
        // dd($semester_aktif->id_semester);
        $id_reg = auth()->user()->fk_id;
        
        $data = PengajuanCuti::where('id_registrasi_mahasiswa', $id_reg)->get();
        // dd($data_mahasiswa->biodata->id_mahasiswa);

        return view('mahasiswa.pengajuan-cuti.index', ['data' => $data]);
    }

    public function tambah()
    {
        // dd($semester_aktif->id_semester);
        $id_reg = auth()->user()->fk_id;
        $data = RiwayatPendidikan::where('id_registrasi_mahasiswa', $id_reg)->first();

        return view('mahasiswa.pengajuan-cuti.store', ['data' => $data]);
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

        $id_cuti = Uuid::uuid4()->toString();
        
        $fileName = 'file_pendukung_' . str_replace(' ', '_', $riwayat_pendidikan->nama_mahasiswa) . '_' . time() . '.' . $request->file('file_pendukung')->getClientOriginalExtension();

        // Simpan file ke folder public/pdf dengan nama kustom
        $filePath = $request->file('file_pendukung')->storeAs('pdf', $fileName, 'public');

        PengajuanCuti::create([
            'id_cuti' => $id_cuti,
            'id_registrasi_mahasiswa' => $id_reg,
            'nama_mahasiswa' => $riwayat_pendidikan->nama_mahasiswa,
            'id_semester' => $semester_aktif->id_semester,
            'nama_semester'=> $semester_aktif->semester->nama_semester,
            'alasan_cuti' => $request->alasan_cuti[0],
            'file_pendukung' => $filePath,
            'approved' => 0,
            'status_sync' => 'belum sync',
        ]);
        
        // Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('mahasiswa.pengajuan-cuti.index')->with('success', 'Data Berhasil di Tambahkan');
    }
}
