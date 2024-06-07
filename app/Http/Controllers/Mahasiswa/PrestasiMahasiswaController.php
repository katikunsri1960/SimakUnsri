<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Mahasiswa\PrestasiMahasiswa;
use App\Models\Referensi\JenisPrestasi;
use App\Models\Referensi\TingkatPrestasi;
use Illuminate\Http\Request;

class PrestasiMahasiswaController extends Controller
{
    public function prestasi_mahasiswa_non_pendanaan()
    {
        // dd($semester_aktif->id_semester);
        $id_reg_mhs = auth()->user()->fk_id;
        $data_mahasiswa = RiwayatPendidikan::with('biodata')->where('id_registrasi_mahasiswa', $id_reg_mhs)->first();

        $data = PrestasiMahasiswa::where('id_mahasiswa', $data_mahasiswa->biodata->id_mahasiswa)->whereNull('id_aktivitas')->get();
        // dd($data_mahasiswa->biodata->id_mahasiswa);

        return view('mahasiswa.prestasi.index-non-pendanaan', ['data' => $data]);
    }

    public function tambah_prestasi_mahasiswa_non_pendanaan()
    {
        // dd($semester_aktif->id_semester);
        $id_reg_mhs = auth()->user()->fk_id;
        $jenis_prestasi = JenisPrestasi::get();
        $tingkat_prestasi = TingkatPrestasi::get();
        $data = RiwayatPendidikan::where('id_registrasi_mahasiswa', $id_reg_mhs)->first();

        return view('mahasiswa.prestasi.store-non-pendanaan', ['data' => $data]);
    }

    public function store_prestasi_mahasiswa_non_pendanaan(Request $request)
    {
        // dd($request->all());
        //Define variable
        $id_dosen = auth()->user()->fk_id;
        $semester_aktif = SemesterAktif::with(['semester'])->first();

        //Validate request data
        $data = $request->validate([
            'link_rps' => 'required',
        ]);

        $data_dosen = DosenPengajarKelasKuliah::with('kelas_kuliah')->whereHas('kelas_kuliah', function ($query) use ($matkul, $semester_aktif){
            $query->where('id_matkul', $matkul)->where('id_semester', $semester_aktif->id_semester);
        })
        ->where('id_dosen', $id_dosen)
        ->where('urutan', '1')
        ->first();

        // dd($data_dosen);
        if($data_dosen){

            MataKuliah::where('id_matkul', $matkul)->update(['link_rps'=> $request->link_rps]);

        }else{
            return redirect()->back()->with('error', 'Anda Bukan Koordinator Mata Kuliah');
        }
        return redirect()->back()->with('success', 'Data Berhasil di Tambahkan');
    }
}
