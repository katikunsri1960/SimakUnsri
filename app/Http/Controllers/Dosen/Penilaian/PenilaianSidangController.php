<?php

namespace App\Http\Controllers\Dosen\Penilaian;

use App\Http\Controllers\Controller;
use App\Models\Perkuliahan\AktivitasMahasiswa;
use Illuminate\Http\Request;

class PenilaianSidangController extends Controller
{
    public function index()
    {
        $db = new AktivitasMahasiswa;
        $data = $db->uji_dosen(auth()->user()->fk_id);
        // dd($data);
        return view('dosen.penilaian.penilaian-sidang.index', [
            'data' => $data
        ]);
    }

    public function approve_penguji(AktivitasMahasiswa $aktivitas)
    {
        // dd($aktivitas);
        $id_dosen = auth()->user()->fk_id;
        $aktivitas->uji_mahasiswa()->where('id_dosen', $id_dosen)->update([
            'status_uji_mahasiswa' => 2
        ]);

        return redirect()->back()->with('success', 'Data berhasil disimpan');
    }

    public function pembatalan_penguji(AktivitasMahasiswa $aktivitas)
    {
        // dd($request->alasan_pembatalan);
        $id_dosen = auth()->user()->fk_id;
        $aktivitas->uji_mahasiswa()->where('id_dosen', $id_dosen)->update([
            'status_uji_mahasiswa' => 3
        ]);

        return redirect()->back()->with('success', 'Data berhasil dibatalkan');
    }
}
