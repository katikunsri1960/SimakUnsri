<?php

namespace App\Http\Controllers\Prodi\Akademik;

use App\Http\Controllers\Controller;
use App\Models\Perkuliahan\AktivitasMahasiswa;
use App\Models\SemesterAktif;
use Illuminate\Http\Request;

class TugasAkhirController extends Controller
{
    public function index(Request $request)
    {
        $semesterAktif = SemesterAktif::first();
        $semester = $semesterAktif->id_semester;
        $db = new AktivitasMahasiswa();
        $data = $db->ta(auth()->user()->fk_id, $semester );
        // dd($data);
        return view('prodi.data-akademik.tugas-akhir.index', [
            'data' => $data,
            'semester' => $semester,
        ]);
    }

    public function approve_pembimbing(AktivitasMahasiswa $aktivitasMahasiswa)
    {
        $db = new AktivitasMahasiswa();
        $result = $db->approve_pembimbing($aktivitasMahasiswa->id_aktivitas);

        return redirect()->back()->with('success', 'Data berhasil disimpan');
    }

    public function edit_pembimbing(AktivitasMahasiswa $aktivitasMahasiswa, Request $request)
    {
        $data = $request->validate([
                    'id_dosen' => 'required|exists:biodata_dosens,id_dosen',
                ]);
        $db = new AktivitasMahasiswa();
        $result = $db->edit_pembimbing($aktivitasMahasiswa->id_aktivitas);

        return redirect()->back()->with('success', 'Data berhasil disimpan');
    }


}
