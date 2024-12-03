<?php

namespace App\Http\Controllers\Dosen\Perkuliahan;

use App\Http\Controllers\Controller;
use App\Models\SemesterAktif;
use App\Models\Perkuliahan\DosenPengajarKelasKuliah;
use App\Models\Perkuliahan\PesertaKelasKuliah;
use Illuminate\Http\Request;

class JadwalKuliahController extends Controller
{
    public function jadwal_kuliah()
    {
        $semester_aktif = SemesterAktif::with(['semester'])->first();
        $id_dosen = auth()->user()->fk_id;

        $data = DosenPengajarKelasKuliah::with(['kelas_kuliah', 'kelas_kuliah.semester', 'kelas_kuliah.ruang_perkuliahan', 'kelas_kuliah.ruang_ujian', 'kelas_kuliah.matkul'])->where('dosen_pengajar_kelas_kuliahs.id_dosen', $id_dosen)->where('dosen_pengajar_kelas_kuliahs.id_semester', $semester_aktif->id_semester)->get();

        return view('dosen.perkuliahan.jadwal-kuliah',['data' => $data]);
    }

    public function detail_kelas_kuliah($kelas){
        $semester_aktif = SemesterAktif::with(['semester'])->first();
        $id_dosen = auth()->user()->fk_id;

        $data = DosenPengajarKelasKuliah::LeftJoin('kelas_kuliahs', 'kelas_kuliahs.id_kelas_kuliah', 'dosen_pengajar_kelas_kuliahs.id_kelas_kuliah')
        ->LeftJoin('biodata_dosens', 'biodata_dosens.id_dosen', 'dosen_pengajar_kelas_kuliahs.id_dosen')
        ->LeftJoin('semesters', 'semesters.id_semester', 'dosen_pengajar_kelas_kuliahs.id_semester')
        ->LeftJoin('ruang_perkuliahans', 'ruang_perkuliahans.id', 'kelas_kuliahs.ruang_perkuliahan_id')
        ->LeftJoin('mata_kuliahs', 'mata_kuliahs.id_matkul', 'kelas_kuliahs.id_matkul')
        ->where('dosen_pengajar_kelas_kuliahs.id_kelas_kuliah', $kelas)
        ->where('dosen_pengajar_kelas_kuliahs.id_semester', $semester_aktif->id_semester)
        ->orderby('dosen_pengajar_kelas_kuliahs.urutan', 'asc')
        ->get();

        $peserta_kelas = PesertaKelasKuliah::where('id_kelas_kuliah', $kelas)->where('approved', 1)->get();

        return view('dosen.perkuliahan.detail-kelas-kuliah',['data' => $data, 'peserta' => $peserta_kelas]);
    }
}
