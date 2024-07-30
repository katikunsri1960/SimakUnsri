<?php

namespace App\Http\Controllers\Mahasiswa\Akademik;

use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use App\Models\SemesterAktif;
use App\Http\Controllers\Controller;
use App\Models\Mahasiswa\AktivitasMagang;
use App\Models\Mahasiswa\RiwayatPendidikan;

class AktivitasMagangController extends Controller
{
    public function index()
    {
        $id_reg = auth()->user()->fk_id;
        
        $data = AktivitasMagang::where('id_registrasi_mahasiswa', $id_reg)->get();

        return view('mahasiswa.perkuliahan.ksm.aktivitas-magang.index', ['data' => $data]);
    }

    public function tambah()
    {
        $id_reg = auth()->user()->fk_id;
        $data = RiwayatPendidikan::where('id_registrasi_mahasiswa', $id_reg)->first();

        return view('mahasiswa.perkuliahan.ksm.aktivitas-magang.store', ['data' => $data]);
    }

    public function store(Request $request)
    {
        $id_reg = auth()->user()->fk_id;
        $semester_aktif = SemesterAktif::first();

        $riwayat_pendidikan = RiwayatPendidikan::select('*')
                    ->where('id_registrasi_mahasiswa', $id_reg)
                    ->first();

        $existingMagang = AktivitasMagang::where('id_registrasi_mahasiswa', $id_reg)
        ->where('id_semester', $semester_aktif->id_semester)
        ->first();
        
        if (!empty($existingMagang)) {
            if ($existingMagang->approved == 0) {
                return redirect()->back()->with('error', 'Anda sudah memiliki pengajuan cuti yang sedang diproses. Tunggu persetujuan atau batalkan pengajuan sebelum membuat pengajuan baru.');
            } elseif ($existingMagang->approved == 1) {
                return redirect()->back()->with('error', 'Anda sudah memiliki pengajuan cuti yang sudah disetujui.');
            }
        }

        // dd($request);
        
        $request->validate([
            'nama_instansi' => 'required',
            'lokasi' => 'required',
        ]);

        $id_aktivitas = Uuid::uuid4()->toString();
        
        AktivitasMagang::create([
            'id_aktivitas' => $id_aktivitas,
            'id_registrasi_mahasiswa' => $id_reg,
            'nama_mahasiswa' => $riwayat_pendidikan->nama_mahasiswa,
            'id_semester' => $semester_aktif->id_semester,
            'nama_semester' => $semester_aktif->semester->nama_semester,
            'nama_instansi' => $request->nama_instansi,
            'lokasi' => $request->lokasi,
            'approved' => 0,
            'status_sync' => 'belum sync',
        ]);
        
        return redirect()->route('mahasiswa.perkuliahan.aktivitas-magang.index')->with('success', 'Data Berhasil di Tambahkan');
    }
}
