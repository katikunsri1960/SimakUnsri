<?php

namespace App\Http\Controllers\Dosen\Perkuliahan;

use App\Http\Controllers\Controller;
use App\Models\SemesterAktif;
use App\Models\Perkuliahan\DosenPengajarKelasKuliah;
use Illuminate\Http\Request;

class JadwalKuliahController extends Controller
{
    public function jadwal_kuliah()
    {
        $semester_aktif = SemesterAktif::with(['semester'])->first();
        $id_dosen = auth()->user()->fk_id;

        $data = DosenPengajarKelasKuliah::LeftJoin('kelas_kuliahs', 'kelas_kuliahs.id_kelas_kuliah', 'dosen_pengajar_kelas_kuliahs.id_kelas_kuliah')->LeftJoin('ruang_perkuliahans', 'ruang_perkuliahans.id', 'kelas_kuliahs.ruang_perkuliahan_id')->LeftJoin('mata_kuliahs', 'mata_kuliahs.id_matkul', 'kelas_kuliahs.id_matkul')->where('dosen_pengajar_kelas_kuliahs.id_dosen', $id_dosen)->where('dosen_pengajar_kelas_kuliahs.id_semester', $semester_aktif->id_semester)->get();

        return view('dosen.perkuliahan.jadwal-kuliah',['data' => $data]);
    }
}
