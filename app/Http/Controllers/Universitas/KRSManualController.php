<?php

namespace App\Http\Controllers\Universitas;

use App\Models\Semester;
use Illuminate\Http\Request;
use App\Models\SemesterAktif;
use App\Models\BatasIsiKRSManual;
use App\Models\Mahasiswa\LulusDo;
use App\Http\Controllers\Controller;
use App\Models\Mahasiswa\RiwayatPendidikan;

class KRSManualController extends Controller
{
    public function index(Request $request)
    {
        $data = BatasIsiKRSManual::with(['riwayat'])->get();
        // $semester = Semester::orderBy('id_semester', 'desc')->get();

        return view('universitas.batas-isi-krs-manual.index', compact('data'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_registrasi_mahasiswa' => 'required|exists:riwayat_pendidikans,id_registrasi_mahasiswa',
            'status_bayar' => 'required | in:0,1,2,3',
            'batas_isi_krs'=>'required | date'
        ]);

        $riwayat_pendidikan= RiwayatPendidikan::where('id_registrasi_mahasiswa', $data['id_registrasi_mahasiswa'])->first();
        // $check = LulusDo::where('id_registrasi_mahasiswa', $data['id_registrasi_mahasiswa'])->first();

        // if ($check) {
        //     return redirect()->back()->with('error', 'Mahasiswa sudah ada pada data Lulus Do Feeder!!');
        // }
        // dd($request->tanggal_pembayaran);
        // $data['id_semester'] = SemesterAktif::first()->id_semester;
        $data['nim'] = $riwayat_pendidikan->nim;
        $data['nama_mahasiswa'] = $riwayat_pendidikan->nama_mahasiswa;
        $data['keterangan'] = $request->keterangan;

        BatasIsiKRSManual::create($data);

        return redirect()->back()->with('success', 'Data berhasil disimpan');
    }

    public function update(BatasIsiKRSManual $idmanual, Request $request)
    {
        $data = $request->validate([
            'batas_isi_krs'=>'required | date'
        ]);

        $idmanual->update($data);

        return redirect()->back()->with('success', 'Data berhasil diubah');
    }

    public function destroy(BatasIsiKRSManual $idmanual)
    {
        $idmanual->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }
}
