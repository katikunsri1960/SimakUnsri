<?php

namespace App\Http\Controllers\Dosen\Pembimbing;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\SemesterAktif;
use Illuminate\Http\Request;

class PembimbingMahasiswaController extends Controller
{
    public function bimbingan_akademik()
    {
        $semester = SemesterAktif::first()->id_semester;

        $data = RiwayatPendidikan::with(['aktivitas_kuliah', 'prodi'])->whereHas('aktivitas_kuliah', function($query) use ($semester) {
                    $query->where('id_semester', $semester);
                })->where('dosen_pa', auth()->user()->fk_id)
                ->get();

        // dd($data);
        return view('dosen.pembimbing.akademik.index', [
            'data' => $data
        ]);
    }

    public function bimbingan_non_akademik()
    {
        return view('dosen.pembimbing.bimbingan-non-akademik');
    }

    public function bimbingan_tugas_akhir()
    {
        return view('dosen.pembimbing.bimbingan-tugas-akhir');
    }
}
