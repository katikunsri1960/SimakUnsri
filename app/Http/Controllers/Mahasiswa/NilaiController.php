<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Perkuliahan\AktivitasKuliahMahasiswa;
use App\Models\Perkuliahan\NilaiPerkuliahan;
use App\Models\Perkuliahan\TranskripMahasiswa;
use Illuminate\Http\Request;

class NilaiController extends Controller
{
    public function index()
    {   
        $id_reg_mhs = auth()->user()->fk_id;
        $aktivitas_kuliah=AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa',$id_reg_mhs)->orderBy('id_semester','desc')->get();
        $transkrip_mahasiswa=TranskripMahasiswa::where('id_registrasi_mahasiswa',$id_reg_mhs)->orderBy('smt_diambil','asc')->get();

        return view('mahasiswa.nilai-perkuliahan.index', ['data_aktivitas' => $aktivitas_kuliah, 'transkrip' => $transkrip_mahasiswa]);
    }

    public function lihat_khs($id_semester)
    {
        $id_reg_mhs = auth()->user()->fk_id;
        $aktivitas_kuliah=AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa',$id_reg_mhs)->where('id_semester', $id_semester)->get();
        $nilai_mahasiswa = NilaiPerkuliahan::with('dosen_pengajar')->where('id_registrasi_mahasiswa', $id_reg_mhs)->where('id_semester', $id_semester)->orderBy('nama_mata_kuliah','asc')->get();
        $transkrip_mahasiswa=TranskripMahasiswa::where('id_registrasi_mahasiswa',$id_reg_mhs)->orderBy('smt_diambil','asc')->get();
        // dd($dosen_pengajar[0]);

        return view('mahasiswa.nilai-perkuliahan.include.detail-khs', ['data_nilai' => $nilai_mahasiswa, 'data_aktivitas' => $aktivitas_kuliah, 'transkrip' => $transkrip_mahasiswa]);
    }
}
