<?php

namespace App\Http\Controllers\Prodi\Aktivitas;

use App\Http\Controllers\Controller;
use App\Models\Perkuliahan\AktivitasMahasiswa;
use App\Models\Perkuliahan\AnggotaAktivitasMahasiswa;
use App\Models\Perkuliahan\BimbingMahasiswa;
use App\Models\SemesterAktif;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AktivitasMahasiswaController extends Controller
{
    public function aktivitas_penelitian()
    {
        return view('prodi.data-aktivitas.aktivitas-penelitian.index');
    }

    public function aktivitas_lomba()
    {
        return view('prodi.data-aktivitas.aktivitas-lomba.index');
    }

    public function aktivitas_organisasi()
    {
        return view('prodi.data-aktivitas.aktivitas-organisasi.index');
    }

    public function aktivitas_pa(Request $request)
    {

        $db = new BimbingMahasiswa();

        $id_prodi = auth()->user()->fk_id;

        $semester = SemesterAktif::first()->id_semester;

        $data = $db->aktivitas_pa_prodi($id_prodi, $semester);
        // dd($data);
        return view('prodi.data-aktivitas.aktivitas-pa.index', [
            'data' => $data
        ]);
    }

    public function aktivitas_pa_update($id, Request $request)
    {
        $data = $request->validate([
            'sk_tugas' => 'required',
            'tanggal_sk_tugas' => 'required'
        ]);

        $aktivitas = AktivitasMahasiswa::where('id', $id)->first();

        $data['tanggal_sk_tugas'] = Carbon::parse($data['tanggal_sk_tugas'])->format('Y-m-d');

        $aktivitas->update([
            'feeder' => 0,
            'sk_tugas' => $data['sk_tugas'],
            'tanggal_sk_tugas' => $data['tanggal_sk_tugas']
        ]);

        return redirect()->back()->with('success', 'Data berhasil diupdate');
    }

    public function anggota_pa($id)
    {
        $aktivitas = AktivitasMahasiswa::where('id',$id)->select('id_aktivitas')->first();
        $bimbingan = BimbingMahasiswa::where('id_aktivitas', $aktivitas->id_aktivitas)->first();

        $data = AnggotaAktivitasMahasiswa::where('id_aktivitas', $aktivitas->id_aktivitas)
                                        ->select('nim', 'nama_mahasiswa')->get();

        return view('prodi.data-aktivitas.aktivitas-pa.anggota', [
            'data' => $data,
            'bimbingan' => $bimbingan,
            'aktivitas' => $aktivitas
        ]);
    }
}
