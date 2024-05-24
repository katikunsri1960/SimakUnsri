<?php

namespace App\Http\Controllers\Dosen\Penilaian;

use App\Http\Controllers\Controller;
use App\Models\Perkuliahan\KelasKuliah;
use App\Models\Perkuliahan\KomponenEvaluasiKelas;
use Illuminate\Http\Request;

class PresentasePenilaianController extends Controller
{
    public function komponen_evaluasi(string $kelas)
    {
        $data_kelas = KelasKuliah::where('id_kelas_kuliah', $kelas)->get();
        $data_komponen = KomponenEvaluasiKelas::where('id_kelas_kuliah', $kelas)->get();
        // dd($data_komponen);
        return view('dosen.penilaian.presentase.komponen-evaluasi', [
            'data' => $data_komponen,
            'kelas' => $data_kelas
        ]);
    }

    public function komponen_evaluasi_store(Request $request, string $kelas)
    {
        // dd($request->all());
        //Define variable
        $prodi_id = auth()->user()->fk_id;
        $kelas = KelasKuliah::where('id_prodi',$prodi_id)->where('id_matkul',$id_matkul)->where('nama_kelas_kuliah',$nama_kelas_kuliah)->get();
        $semester_aktif = SemesterAktif::first();

        //Validate request data
        $data = $request->validate([
            'participatory' => 'required',
            'project_outcomes' => 'required',
            'assignment' => 'required',
            'quiz' => 'required',
            'midterm_exam' => 'required',
            'finalterm_exam' => 'required'
        ]);

        //Get id dosen pengajar kelas kuliah
        $dosen_pengajar = PenugasanDosen::where('id_tahun_ajaran',$semester_aktif->semester->id_tahun_ajaran)->whereIn('id_registrasi_dosen', $request->dosen_kelas_kuliah)->get();

        if($rencana_pertemuan == '16'){
             //Count jumlah dosen pengajar kelas kuliah
            $jumlah_dosen=count($request->dosen_kelas_kuliah);

            for($i=0;$i<$jumlah_dosen;$i++){
                //Generate id aktivitas mengajar
                $id_aktivitas_mengajar = Uuid::uuid4()->toString();

                if(is_null($request->substansi_kuliah)){
                    //Store data to table tanpa substansi kuliah
                    DosenPengajarKelasKuliah::create(['id_aktivitas_mengajar'=> $id_aktivitas_mengajar, 'id_registrasi_dosen'=> $dosen_pengajar[$i]['id_registrasi_dosen'], 'id_dosen'=> $dosen_pengajar[$i]['id_dosen'], 'id_kelas_kuliah'=> $kelas[0]['id_kelas_kuliah'], 'rencana_minggu_pertemuan'=> $request->rencana_minggu_pertemuan[$i], 'realisasi_minggu_pertemuan'=> 0, 'id_jenis_evaluasi'=> $request->evaluasi[$i], 'id_prodi'=> $prodi_id, 'id_semester'=> $semester_aktif->id_semester]);

                }else{
                    //Get sks substansi total
                    $substansi_kuliah = SubstansiKuliah::where('id_substansi',$request->substansi_kuliah[$i])->get();

                    //Store data to table dengan substansi kuliah
                    DosenPengajarKelasKuliah::create(['id_aktivitas_mengajar'=> $id_aktivitas_mengajar, 'id_registrasi_dosen'=> $dosen_pengajar[$i]['id_registrasi_dosen'], 'id_dosen'=> $dosen_pengajar[$i]['id_dosen'], 'id_kelas_kuliah'=> $kelas[0]['id_kelas_kuliah'], 'id_substansi' => $substansi_kuliah->first()->id_substansi, 'sks_substansi_total' => $substansi_kuliah->first()->sks_mata_kuliah, 'rencana_minggu_pertemuan'=> $request->rencana_minggu_pertemuan[$i], 'realisasi_minggu_pertemuan'=> 0, 'id_jenis_evaluasi'=> $request->evaluasi[$i], 'id_prodi'=> $prodi_id, 'id_semester'=> $semester_aktif->id_semester]);
                }

            }
            return redirect()->back()->with('success', 'Data Berhasil di Tambahkan');
        }else{
            return redirect()->back()->with('error', 'Total Rencana Minggu Pertemuan Dosen Tidak Berjumlah 16');
        }
    }
}
