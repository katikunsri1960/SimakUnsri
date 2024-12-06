<?php

namespace App\Http\Controllers\Dosen\Penilaian;

use App\Http\Controllers\Controller;
use App\Models\Dosen\BiodataDosen;
use App\Models\Perkuliahan\KelasKuliah;
use App\Models\Perkuliahan\KomponenEvaluasiKelas;
use App\Models\Perkuliahan\NilaiKomponenEvaluasi;
use App\Models\Perkuliahan\DosenPengajarKelasKuliah;
use App\Models\SemesterAktif;
use App\Exports\ExportDPNA;
use App\Imports\ImportDPNA;
use App\Models\SkalaNilai;
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

        // dd($data);

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
        $data_kelas = KelasKuliah::with('matkul')->where('id_kelas_kuliah', $kelas)->first();
        $data_komponen = KomponenEvaluasiKelas::where('id_kelas_kuliah', $kelas)->get();
        $semester_aktif = SemesterAktif::first();

        //Check batas pengisian nilai
        $hari_proses = date('Y-m-d');
        $batas_nilai = $semester_aktif->batas_isi_nilai;

        $checkSkala = $this->checkSkalaNilai($data_kelas->id_prodi);

        if(!$checkSkala){
            return redirect()->back()->with('error', 'Sekala nilai prodi pada feeder belum diatur! Silahkan hubungi Admin Universitas untuk mengatur skala nilai feeder');
        }

        if(!$data_komponen->isEmpty()){
            if($hari_proses <= $batas_nilai){
                // remove regex from $data_kelas->matkul->nama_mata_kuliah
                $nm_matkul = preg_replace('/[^A-Za-z0-9\-]/', '_', $data_kelas->matkul->nama_mata_kuliah);
                return Excel::download(new ExportDPNA($kelas, $prodi), 'DPNA_'.$data_kelas->nama_program_studi.'_'.$data_kelas->matkul->kode_mata_kuliah.'_'.$nm_matkul.'_'.$data_kelas->nama_kelas_kuliah.'.xlsx');
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
        $data_kelas = KelasKuliah::with('matkul')->where('id_kelas_kuliah', $kelas)->first();
        $nilai_komponen = NilaiKomponenEvaluasi::where('id_kelas', $kelas)->get();
        $id_dosen = auth()->user()->fk_id;
        $data_dosen = DosenPengajarKelasKuliah::where('id_kelas_kuliah', $kelas)->where('id_dosen', $id_dosen)->first();

        if($data_dosen->urutan != 1){
            return redirect()->back()->with('error', 'Anda bukan koordinator kelas kuliah.');
        }

        // List of program codes not requiring scheduling checks
        $prodi_not_scheduled = ['11706', '11707', '11708', '11711', '11718', '11702', '11704', '11701', '11703', '11705', '11728', '11735', '12901', '11901', '14901', '23902', '86904', '48901'];

        // Fetch data for the specific class and its associated program
        $data_prodi = KelasKuliah::with('prodi')->where('id_kelas_kuliah', $kelas)->first();

        // Check the schedule for inputting grades
        $hari_proses = date('Y-m-d');
        $mulai_nilai = $semester_aktif->mulai_isi_nilai;
        $batas_nilai = $semester_aktif->batas_isi_nilai;

        // Check if the program is not in the excluded list
        if (!in_array($data_prodi->prodi->kode_program_studi, $prodi_not_scheduled)) {

            if ($hari_proses < $mulai_nilai) {
                return redirect()->back()->with('error', "Jadwal Pengisian Nilai Belum Dimulai!");
            } elseif ($hari_proses > $batas_nilai) {
                return redirect()->back()->with('error', "Jadwal Pengisian Nilai Telah Berakhir!");
            }
        }

        return view('dosen.penilaian.penilaian-perkuliahan.upload-dpna', [
            'data' => $nilai_komponen,
            'kelas' => $data_kelas,
            'mulai_pengisian' => $mulai_nilai,
            'batas_pengisian' => $batas_nilai
        ]);
    }

    public function upload_dpna_store(Request $request, string $kelas, string $matkul)
    {
        $semester_aktif = SemesterAktif::first();
        $id_dosen = auth()->user()->fk_id;
        $data_dosen = DosenPengajarKelasKuliah::where('id_kelas_kuliah', $kelas)->where('id_dosen', $id_dosen)->first();

        if($data_dosen->urutan != 1){
            return redirect()->back()->with('error', 'Anda bukan koordinator kelas kuliah.');
        }

        // Validate the uploaded file
        $data = $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:2048', // Validate file type and size
        ]);

        // List of program codes not requiring scheduling checks
        $prodi_not_scheduled = ['11706', '11707', '11708', '11711', '11718', '11702', '11704', '11701', '11703', '11705', '11728', '11735', '12901', '11901', '14901', '23902', '86904', '48901'];

        // Fetch data for the specific class and its associated program
        $data_prodi = KelasKuliah::with('prodi')->where('id_kelas_kuliah', $kelas)->first();

        // Check if the program is not in the excluded list
        if (!in_array($data_prodi->prodi->kode_program_studi, $prodi_not_scheduled)) {
            // Check the schedule for inputting grades
            $hari_proses = date('Y-m-d');
            $mulai_nilai = $semester_aktif->mulai_isi_nilai;
            $batas_nilai = $semester_aktif->batas_isi_nilai;

            if ($hari_proses < $mulai_nilai) {
                return redirect()->back()->with('error', "Jadwal Pengisian Nilai Belum Dimulai!");
            } elseif ($hari_proses > $batas_nilai) {
                return redirect()->back()->with('error', "Jadwal Pengisian Nilai Telah Berakhir!");
            }
        }

        // If everything is valid, process the file import
        $file = $request->file('file');
        Excel::import(new ImportDPNA($kelas, $matkul), $file);

        return redirect()->back()->with('success', "Data successfully imported!");
    }

    private function checkSkalaNilai($id_prodi)
    {
        $data = SkalaNilai::where('id_prodi', $id_prodi)->get();

        // check if the data is empty or there the count data is less than 5
        if ($data->isEmpty() || $data->count() < 5) {
            return false;
        } else {
            return true;
        }
    }
}
