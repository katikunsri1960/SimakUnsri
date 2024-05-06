<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Perkuliahan\AktivitasKuliahMahasiswa;
use App\Models\Perkuliahan\NilaiPerkuliahan;
use App\Models\Perkuliahan\TranskripMahasiswa;
use App\Models\Perkuliahan\KonversiAktivitas;
use App\Models\Perkuliahan\NilaiTransferPendidikan;
use Illuminate\Http\Request;

class NilaiController extends Controller
{
    public function index()
    {   
        $id_reg_mhs = auth()->user()->fk_id;

        $aktivitas_kuliah=AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa',$id_reg_mhs)->orderBy('id_semester','desc')->get();
        $nilai_transfer=NilaiTransferPendidikan::where('id_registrasi_mahasiswa',$id_reg_mhs)->orderBy('id_semester','asc')->get();
        $nilai_konversi=KonversiAktivitas::leftJoin('anggota_aktivitas_mahasiswas', 'anggota_aktivitas_mahasiswas.id_anggota', 'konversi_aktivitas.id_anggota')
                        ->leftJoin('mata_kuliahs', 'mata_kuliahs.id_matkul', 'konversi_aktivitas.id_matkul')
                        ->where('id_registrasi_mahasiswa',$id_reg_mhs)
                        ->orderBy('id_semester','asc')
                        ->get();
        $transkrip_mahasiswa=NilaiPerkuliahan::where('id_registrasi_mahasiswa',$id_reg_mhs)->orderBy('id_semester','asc')->get();
        
        return view('mahasiswa.nilai-perkuliahan.index', ['data_aktivitas' => $aktivitas_kuliah, 'transkrip' => $transkrip_mahasiswa, 'nilai_konversi' => $nilai_konversi, 'nilai_transfer' => $nilai_transfer]);
    }

    public function lihat_khs($id_semester)
    {
        $id_reg_mhs = auth()->user()->fk_id;
        $aktivitas_kuliah=AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa',$id_reg_mhs)->where('id_semester', $id_semester)->get();
        $nilai_mahasiswa = NilaiPerkuliahan::with('dosen_pengajar')->where('id_registrasi_mahasiswa', $id_reg_mhs)->where('id_semester', $id_semester)->orderBy('nama_mata_kuliah','asc')->get();
        $transkrip_mahasiswa=NilaiPerkuliahan::where('id_registrasi_mahasiswa',$id_reg_mhs)->orderBy('id_semester','asc')->get();
        $nilai_transfer=NilaiTransferPendidikan::where('id_registrasi_mahasiswa',$id_reg_mhs)->orderBy('id_semester','asc')->get();
        $nilai_konversi=KonversiAktivitas::leftJoin('anggota_aktivitas_mahasiswas', 'anggota_aktivitas_mahasiswas.id_anggota', 'konversi_aktivitas.id_anggota')
                        ->leftJoin('mata_kuliahs', 'mata_kuliahs.id_matkul', 'konversi_aktivitas.id_matkul')
                        ->where('id_registrasi_mahasiswa',$id_reg_mhs)
                        ->orderBy('id_semester','asc')
                        ->get();
        // dd($dosen_pengajar[0]);

        return view('mahasiswa.nilai-perkuliahan.include.detail-khs', ['data_nilai' => $nilai_mahasiswa, 'data_aktivitas' => $aktivitas_kuliah, 'transkrip' => $transkrip_mahasiswa, 'nilai_konversi' => $nilai_konversi, 'nilai_transfer' => $nilai_transfer]);
    }
    public function histori_nilai($id_matkul)
    {
        $id_reg_mhs = auth()->user()->fk_id;
        $aktivitas_kuliah=AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa',$id_reg_mhs)->orderBy('id_semester','desc')->get();
        $transkrip_mahasiswa=NilaiPerkuliahan::where('id_registrasi_mahasiswa',$id_reg_mhs)->where('id_matkul', $id_matkul)->orderBy('id_semester','asc')->get();
        $nilai_transfer=NilaiTransferPendidikan::where('id_registrasi_mahasiswa',$id_reg_mhs)->where('id_matkul', $id_matkul)->orderBy('id_semester','asc')->get();
        $nilai_konversi=KonversiAktivitas::leftJoin('anggota_aktivitas_mahasiswas', 'anggota_aktivitas_mahasiswas.id_anggota', 'konversi_aktivitas.id_anggota')
                        ->leftJoin('mata_kuliahs', 'mata_kuliahs.id_matkul', 'konversi_aktivitas.id_matkul')
                        ->where('id_registrasi_mahasiswa',$id_reg_mhs)
                        ->where('konversi_aktivitas.id_matkul', $id_matkul)
                        ->orderBy('id_semester','asc')
                        ->get();
        // dd($dosen_pengajar[0]);

        return view('mahasiswa.nilai-perkuliahan.include.histori-nilai', ['data_aktivitas' => $aktivitas_kuliah,'transkrip' => $transkrip_mahasiswa, 'nilai_konversi' => $nilai_konversi, 'nilai_transfer' => $nilai_transfer]);
    }
}
