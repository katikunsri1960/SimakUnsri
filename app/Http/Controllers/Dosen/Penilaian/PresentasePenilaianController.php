<?php

namespace App\Http\Controllers\Dosen\Penilaian;

use App\Http\Controllers\Controller;
use App\Models\Perkuliahan\KelasKuliah;
use App\Models\Perkuliahan\KomponenEvaluasiKelas;
use App\Models\Perkuliahan\DosenPengajarKelasKuliah;
use App\Models\SemesterAktif;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;

class PresentasePenilaianController extends Controller
{
    public function komponen_evaluasi(string $kelas)
    {
        $semester_aktif = SemesterAktif::first();
        $data_kelas = KelasKuliah::with(['matkul', 'prodi'])->where('id_kelas_kuliah', $kelas)->first();
        $data_komponen = KomponenEvaluasiKelas::where('id_kelas_kuliah', $kelas)->get();

         // List of program codes not requiring scheduling checks
         $prodi_not_scheduled = ['12201','11201','14201','11706', '11707', '11708', '11711', '11718', '11702', '11704', '11701', '11703', '11705', '11728', '11735', '12901', '11901', '14901', '23902', '86904', '48901'];

        //Check batas pengisian nilai
        $interval = $semester_aktif->batas_isi_nilai;

        if(!$data_komponen->isEmpty()){
            foreach($data_komponen as $d){
                if($d->id_jenis_evaluasi == '2'){
                    $bobot_participatory = $d->bobot_evaluasi;
                }
                else if($d->id_jenis_evaluasi == '3'){
                    $bobot_project = $d->bobot_evaluasi;
                }
                elseif($d->id_jenis_evaluasi == '4' && $d->nomor_urut == '3'){
                    $bobot_assignment = $d->bobot_evaluasi;
                }
                elseif($d->id_jenis_evaluasi == '4' && $d->nomor_urut == '4'){
                    $bobot_quiz = $d->bobot_evaluasi;
                }
                elseif($d->id_jenis_evaluasi == '4' && $d->nomor_urut == '5'){
                    $bobot_midterm = $d->bobot_evaluasi;
                }
                elseif($d->id_jenis_evaluasi == '4' && $d->nomor_urut == '6'){
                    $bobot_finalterm = $d->bobot_evaluasi;
                }
                else{
                    return redirect()->back()->with('error', 'ID Jenis Evaluasi Tidak Sesuai');
                }
            }
        }else{
            $bobot_participatory = 0;
            $bobot_project = 0;
            $bobot_assignment = 0;
            $bobot_quiz = 0;
            $bobot_midterm = 0;
            $bobot_finalterm = 0;
        }

        // dd($data_komponen);
        return view('dosen.penilaian.presentase.komponen-evaluasi', [
            'data' => $data_komponen,
            'kelas' => $data_kelas,
            'participatory' => $bobot_participatory,
            'project_outcomes' => $bobot_project,
            'assignment' => $bobot_assignment,
            'quiz' => $bobot_quiz,
            'midterm_exam' => $bobot_midterm,
            'finalterm_exam' => $bobot_finalterm,
            'batas_pengisian' => $interval,
            'prodi_bebas_jadwal' => $prodi_not_scheduled
        ]);
    }

    public function komponen_evaluasi_store(Request $request, string $kelas)
    {
        // dd($request->all());
        //Define variable;
        $semester_aktif = SemesterAktif::first();
        $id_dosen = auth()->user()->fk_id;
        $data_dosen = DosenPengajarKelasKuliah::where('id_kelas_kuliah', $kelas)->where('id_dosen', $id_dosen)->first();

        if($data_dosen->urutan != 1){
            return redirect()->back()->with('error', 'Anda bukan koordinator kelas kuliah.');
        }

        //Validate request data
        $data = $request->validate([
            'participatory' => 'required',
            'project_outcomes' => 'required',
            'assignment' => 'required',
            'quiz' => 'required',
            'midterm_exam' => 'required',
            'finalterm_exam' => 'required'
        ]);
        $data_kelas = KelasKuliah::with(['matkul', 'prodi'])->where('id_kelas_kuliah', $kelas)->first();
        //Check komponen kelas kuliah masih kosong
        $komponen_kelas = KomponenEvaluasiKelas::where('id_kelas_kuliah', $kelas)->get();
        $prodi_bebas_jadwal = ['12201','11201','14201','11706', '11707', '11708', '11711', '11718', '11702', '11704', '11701', '11703', '11705', '11728', '11735', '12901', '11901', '14901', '23902', '86904', '48901'];

        // dd($interval);
        if (date('Y-m-d') > $semester_aktif->batas_isi_nilai && !in_array($data_kelas->prodi->kode_program_studi, $prodi_bebas_jadwal)) {
            return redirect()->back()->with('error', 'Komponen Evaluasi Telah di Buat / Cek Batas Pengisian Nilai');
        }

        if($komponen_kelas->count() == 0){
            //Check jumlah bobot komponen evaluasi
            $total_bobot_input = $request->participatory + $request->project_outcomes + $request->assignment + $request->quiz + $request->midterm_exam + $request->finalterm_exam;

            if($total_bobot_input == 100){
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
                KomponenEvaluasiKelas::create(['feeder'=> 0, 'id_komponen_evaluasi'=> $id_komp_eval5, 'id_kelas_kuliah'=> $kelas, 'id_jenis_evaluasi'=> 4,  'nama'=> 'UTS', 'nama_inggris'=> 'Midterm Exam', 'nomor_urut'=> 5, 'bobot_evaluasi'=> $bobot_midterm]);

                //Store data finalterm
                KomponenEvaluasiKelas::create(['feeder'=> 0, 'id_komponen_evaluasi'=> $id_komp_eval6, 'id_kelas_kuliah'=> $kelas, 'id_jenis_evaluasi'=> 4,  'nama'=> 'UAS', 'nama_inggris'=> 'Finalterm Exam', 'nomor_urut'=> 6, 'bobot_evaluasi'=> $bobot_finalterm]);

                return redirect()->back()->with('success', 'Data Berhasil di Tambahkan');
            }else{
                return redirect()->back()->with('error', 'Bobot Komponen Evaluasi Tidak Sama Dengan 100%');
            }
        }else{
            return redirect()->back()->with('error', 'Komponen Evaluasi Telah di Buat / Cek Batas Pengisian Nilai');
        }
    }

    public function komponen_evaluasi_update(Request $request, string $kelas)
    {
        // dd($request->all());
        //Define variable;
        $semester_aktif = SemesterAktif::first();
        $komponen_kelas = KomponenEvaluasiKelas :: where('id_kelas_kuliah', $kelas)->get();
        $data_kelas = KelasKuliah::with(['matkul', 'prodi'])->where('id_kelas_kuliah', $kelas)->first();
        $id_dosen = auth()->user()->fk_id;
        $data_dosen = DosenPengajarKelasKuliah::where('id_kelas_kuliah', $kelas)->where('id_dosen', $id_dosen)->first();

        if($data_dosen->urutan != 1){
            return redirect()->back()->with('error', 'Anda bukan koordinator kelas kuliah.');
        }

        //Validate request data
        $data = $request->validate([
            'participatory' => 'required',
            'project_outcomes' => 'required',
            'assignment' => 'required',
            'quiz' => 'required',
            'midterm_exam' => 'required',
            'finalterm_exam' => 'required'
        ]);

        $prodi_not_scheduled = ['12201','11201','14201','11706', '11707', '11708', '11711', '11718', '11702', '11704', '11701', '11703', '11705', '11728', '11735', '12901', '11901', '14901', '23902', '86904', '48901'];

        if(collect($komponen_kelas)->pluck('feeder')->contains('0') && (date('Y-m-d') <= $semester_aktif->batas_isi_nilai || in_array($data_kelas->prodi->kode_program_studi, $prodi_not_scheduled))){
            //Check jumlah bobot komponen evaluasi
            $total_bobot_input = $request->participatory + $request->project_outcomes + $request->assignment + $request->quiz + $request->midterm_exam + $request->finalterm_exam;

            if($total_bobot_input == 100){

                //Penyesuaian format bobot komponen evaluasi
                $bobot_participatory = $request->participatory/100;
                $bobot_project = $request->project_outcomes/100;
                $bobot_assignment = $request->assignment/100;
                $bobot_quiz = $request->quiz/100;
                $bobot_midterm = $request->midterm_exam/100;
                $bobot_finalterm = $request->finalterm_exam/100;

                //Store data participatory
                KomponenEvaluasiKelas::where('id_komponen_evaluasi', $komponen_kelas[0]->id_komponen_evaluasi)
                                    ->where('id_kelas_kuliah', $kelas)
                                    ->update(['bobot_evaluasi'=> $bobot_participatory]);

                //Store data project
                KomponenEvaluasiKelas::where('id_komponen_evaluasi', $komponen_kelas[1]->id_komponen_evaluasi)
                                    ->where('id_kelas_kuliah', $kelas)
                                    ->update(['bobot_evaluasi'=> $bobot_project]);

                //Store data assignment
                KomponenEvaluasiKelas::where('id_komponen_evaluasi', $komponen_kelas[2]->id_komponen_evaluasi)
                                    ->where('id_kelas_kuliah', $kelas)
                                    ->update(['bobot_evaluasi'=> $bobot_assignment]);

                //Store data quiz
                KomponenEvaluasiKelas::where('id_komponen_evaluasi', $komponen_kelas[3]->id_komponen_evaluasi)
                                    ->where('id_kelas_kuliah', $kelas)
                                    ->update(['bobot_evaluasi'=> $bobot_quiz]);

                //Store data midterm
                KomponenEvaluasiKelas::where('id_komponen_evaluasi', $komponen_kelas[4]->id_komponen_evaluasi)
                                    ->where('id_kelas_kuliah', $kelas)
                                    ->update(['bobot_evaluasi'=> $bobot_midterm]);

                //Store data finalterm
                KomponenEvaluasiKelas::where('id_komponen_evaluasi', $komponen_kelas[5]->id_komponen_evaluasi)
                                    ->where('id_kelas_kuliah', $kelas)
                                    ->update(['bobot_evaluasi'=> $bobot_finalterm]);

                return redirect()->back()->with('success', 'Data Berhasil di Update');
            }else{
                return redirect()->back()->with('error', 'Bobot Komponen Evaluasi Tidak Sama Dengan 100%');
            }
        }else{
            return redirect()->back()->with('error', 'Komponen Evaluasi Telah di Sinkronisasi / Cek Batas Pengisian Nilai');
        }
    }
}
