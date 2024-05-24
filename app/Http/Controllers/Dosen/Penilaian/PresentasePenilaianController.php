<?php

namespace App\Http\Controllers\Dosen\Penilaian;

use App\Http\Controllers\Controller;
use App\Models\Perkuliahan\KelasKuliah;
use App\Models\Perkuliahan\KomponenEvaluasiKelas;
use App\Models\SemesterAktif;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;

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
        //Define variable;
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

        //Check komponen kelas kuliah masih kosong
        $komponen_kelas = KomponenEvaluasiKelas::where('id_kelas_kuliah', $kelas)->get();

        //Check batas pengisian nilai
        $hari_proses = Carbon::now();
        $batas_nilai = Carbon::createFromFormat('Y-m-d', $semester_aktif->batas_isi_nilai);
        $interval = $hari_proses->diffInDays($batas_nilai);

        // dd($interval);

        if($komponen_kelas->isEmpty() && $interval >= 0){
            //Generate id aktivitas mengajar
            $id_komp_eval1 = Uuid::uuid4()->toString();
            $id_komp_eval2 = Uuid::uuid4()->toString();
            $id_komp_eval3 = Uuid::uuid4()->toString();
            $id_komp_eval4 = Uuid::uuid4()->toString();
            $id_komp_eval5 = Uuid::uuid4()->toString();
            $id_komp_eval6 = Uuid::uuid4()->toString();

            //Penyesuaian format bobot komponen evaluasi
            $bobot_participatory = $request->participatory/100;
            $bobot_project = $request->project_outcomes/100;
            $bobot_assignment = $request->assignment/100;
            $bobot_quiz = $request->quiz/100;
            $bobot_midterm = $request->midterm_exam/100;
            $bobot_finalterm = $request->finalterm_exam/100;

            //Store data participatory
            KomponenEvaluasiKelas::create(['feeder'=> 0, 'id_komponen_evaluasi'=> $id_komp_eval1, 'id_kelas_kuliah'=> $kelas, 'id_jenis_evaluasi'=> 2,  'nama'=> '-', 'nama_inggris'=> 'Participatory Activity', 'nomor_urut'=> 1, 'bobot_evaluasi'=> $bobot_participatory]);

            //Store data project
            KomponenEvaluasiKelas::create(['feeder'=> 0, 'id_komponen_evaluasi'=> $id_komp_eval2, 'id_kelas_kuliah'=> $kelas, 'id_jenis_evaluasi'=> 3,  'nama'=> '-', 'nama_inggris'=> 'Project Outcomes', 'nomor_urut'=> 2, 'bobot_evaluasi'=> $bobot_project]);

            //Store data assignment
            KomponenEvaluasiKelas::create(['feeder'=> 0, 'id_komponen_evaluasi'=> $id_komp_eval3, 'id_kelas_kuliah'=> $kelas, 'id_jenis_evaluasi'=> 4,  'nama'=> 'TGS', 'nama_inggris'=> 'Assigment', 'nomor_urut'=> 3, 'bobot_evaluasi'=> $bobot_assignment]);

            //Store data quiz
            KomponenEvaluasiKelas::create(['feeder'=> 0, 'id_komponen_evaluasi'=> $id_komp_eval4, 'id_kelas_kuliah'=> $kelas, 'id_jenis_evaluasi'=> 4,  'nama'=> 'QIZ', 'nama_inggris'=> 'Quiz', 'nomor_urut'=> 4, 'bobot_evaluasi'=> $bobot_quiz]);

            //Store data midterm
            KomponenEvaluasiKelas::create(['feeder'=> 0, 'id_komponen_evaluasi'=> $id_komp_eval5, 'id_kelas_kuliah'=> $kelas, 'id_jenis_evaluasi'=> 4,  'nama'=> 'UTS', 'nama_inggris'=> 'Midterm Exam', 'nomor_urut'=> 5, 'bobot_evaluasi'=> $bobot_quiz]);

            //Store data finalterm
            KomponenEvaluasiKelas::create(['feeder'=> 0, 'id_komponen_evaluasi'=> $id_komp_eval6, 'id_kelas_kuliah'=> $kelas, 'id_jenis_evaluasi'=> 4,  'nama'=> 'UAS', 'nama_inggris'=> 'Finalterm Exam', 'nomor_urut'=> 6, 'bobot_evaluasi'=> $bobot_quiz]);
            
            return redirect()->back()->with('success', 'Data Berhasil di Tambahkan');
        }else{
            return redirect()->back()->with('error', 'Komponen Evaluasi Telah di Buat / Cek Batas Pengisian Nilai');
        }
    }
}
