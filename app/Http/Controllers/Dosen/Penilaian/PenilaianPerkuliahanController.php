<?php

namespace App\Http\Controllers\Dosen\Penilaian;

use App\Http\Controllers\Controller;
use App\Models\Dosen\BiodataDosen;
use App\Models\Perkuliahan\KelasKuliah;
use App\Models\Perkuliahan\KomponenEvaluasiKelas;
use App\Models\Perkuliahan\NilaiKomponenEvaluasi;
use App\Models\SemesterAktif;
use App\Exports\ExportDPNA;
use App\Imports\ImportDPNA;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PenilaianPerkuliahanController extends Controller
{
    public function penilaian_perkuliahan()
    {
        $db = new BiodataDosen;

        $semester_aktif = SemesterAktif::first();
        $data = $db->dosen_pengajar_kelas(auth()->user()->fk_id);

        return view('dosen.penilaian.penilaian-perkuliahan.index', [
            'data' => $data, 'semester_aktif' => $semester_aktif]);
    }

    public function detail_penilaian_perkuliahan(string $kelas)
    {
        $db = new KelasKuliah;
        $data = $db->detail_penilaian_perkuliahan($kelas);

        return view('dosen.penilaian.penilaian-perkuliahan.detail', [
            'data' => $data
        ]);
    }

    public function download_dpna(string $kelas, string $prodi)
    {
        $data_kelas = KelasKuliah::where('id_kelas_kuliah', $kelas)->get();
        $data_komponen = KomponenEvaluasiKelas::where('id_kelas_kuliah', $kelas)->get();
        $semester_aktif = SemesterAktif::first();

        //Check batas pengisian nilai
        $hari_proses = Carbon::now();
        $batas_nilai = Carbon::createFromFormat('Y-m-d', $semester_aktif->batas_isi_nilai);
        $interval = $hari_proses->diffInDays($batas_nilai);
        $akhir_pengisian = $hari_proses->lte($batas_nilai);

        if(!$data_komponen->isEmpty()){
            if($interval >= 0){
                return Excel::download(new ExportDPNA($kelas, $prodi), 'DPNA_'.$data_kelas[0]['nama_program_studi'].'_'.$data_kelas[0]['kode_mata_kuliah'].'_'.$data_kelas[0]['nama_kelas_kuliah'].'.xlsx');
            }else{
                return redirect()->back()->with('error', 'Jadwal Pengisian Nilai Telah Berakhir');
            }
        }else{
            return redirect()->back()->with('error', 'Silahkan Melakukan Pengaturan Bobot Komponen Evaluasi');
        }
    }

    public function upload_dpna(string $kelas)
    {
        $semester_aktif = SemesterAktif::first();
        $data_kelas = KelasKuliah::where('id_kelas_kuliah', $kelas)->get();
        $nilai_komponen = NilaiKomponenEvaluasi::where('id_kelas', $kelas)->get();

        //Check batas pengisian nilai
        $hari_proses = Carbon::now();
        $batas_nilai = Carbon::createFromFormat('Y-m-d', $semester_aktif->batas_isi_nilai);
        $interval = $hari_proses->diffInDays($batas_nilai);
        $akhir_pengisian = $hari_proses->gt($batas_nilai);

        // dd($interval);
        return view('dosen.penilaian.penilaian-perkuliahan.upload-dpna', [
            'data' => $nilai_komponen,
            'kelas' => $data_kelas,
            'interval' => $interval,
            'batas_pengisian' => $akhir_pengisian
        ]);
    }

    public function upload_dpna_store(Request $request, string $kelas, string $matkul)
    {
        // dd($request->all());
        $data = $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:2048', // Validate the file type and size
        ]);

        $file = $request->file('file');
        Excel::import(new ImportDPNA($kelas, $matkul), $file);

        return back()->with('success', 'Data nilai perkuliahan berhasil ditambahkan');
    }
}
