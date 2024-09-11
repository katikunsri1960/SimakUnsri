<?php

namespace App\Http\Controllers\Universitas;

use App\Models\Semester;
use Illuminate\Http\Request;
use App\Models\SemesterAktif;
use App\Models\Mahasiswa\LulusDo;
use App\Http\Controllers\Controller;
use App\Models\PembayaranManualMahasiswa;
use App\Models\Mahasiswa\RiwayatPendidikan;

class PembayaranManualMahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $data = PembayaranManualMahasiswa::with(['semester', 'riwayat'])->filter($request)->get();
        $semester = Semester::orderBy('id_semester', 'desc')->get();

        return view('universitas.pembayaran-manual.index', compact('data', 'semester'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_registrasi_mahasiswa' => 'required|exists:riwayat_pendidikans,id_registrasi_mahasiswa',
            'status' => 'required | in:0,1',
            'tanggal_pembayaran'=>'required | date',
            'nominal_ukt' => 'required'
        ]);

        $check = LulusDo::where('id_registrasi_mahasiswa', $data['id_registrasi_mahasiswa'])->first();

        if ($check) {
            return redirect()->back()->with('error', 'Mahasiswa sudah ada pada data Lulus Do Feeder!!');
        }
        // dd($request->tanggal_pembayaran);
        $data['nominal_ukt'] = str_replace('.', '', $data['nominal_ukt']);
        $data['id_semester'] = SemesterAktif::first()->id_semester;
        $data['nim'] = RiwayatPendidikan::where('id_registrasi_mahasiswa', $data['id_registrasi_mahasiswa'])->first()->nim;
        $data['tanggal_pembayaran'] = $request->tanggal_pembayaran;

        PembayaranManualMahasiswa::create($data);

        return redirect()->back()->with('success', 'Data berhasil disimpan');
    }

    public function update(PembayaranManualMahasiswa $idmanual, Request $request)
    {
        $data = $request->validate([
            'status' => 'required|in:0,1',
        ]);

        $idmanual->update($data);

        return redirect()->back()->with('success', 'Data berhasil diubah');
    }

    public function destroy(PembayaranManualMahasiswa $idmanual)
    {
        $idmanual->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }
}
