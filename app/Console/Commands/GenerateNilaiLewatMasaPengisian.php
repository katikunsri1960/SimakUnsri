<?php

namespace App\Console\Commands;

use App\Models\Dosen\BiodataDosen;
use App\Models\Perkuliahan\KelasKuliah;
use App\Models\Perkuliahan\KomponenEvaluasiKelas;
use App\Models\Perkuliahan\NilaiKomponenEvaluasi;
use App\Models\Perkuliahan\PesertaKelasKuliah;
use App\Models\Perkuliahan\NilaiPerkuliahan;
use App\Models\ProgramStudi;
use App\Models\SemesterAktif;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class GenerateNilaiLewatMasaPengisian extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-nilai-lewat-masa-pengisian';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '2048M');

        $semester_aktif = ['id_semester' => '20252', 'nama_semester' => '2025/2026 Genap']; //SemesterAktif::first();

        if (!$semester_aktif) {
            $this->info('Semester aktif tidak ditemukan');
            return;
        }

        $prodi = ProgramStudi::where('status', 'A')
                ->where('id_prodi', '!=', 'f3a08605-43e6-44eb-97eb-aa04dd55623c') // Exclude Penjas S1
                ->where('fakultas_id', '!=', 4) // Exclude FK
                ->whereHas('kelas_kuliah', function ($query) use ($semester_aktif) {
                    $query->where('id_semester', $semester_aktif['id_semester'])
                        ->whereHas('peserta_kelas_approved')
                        ->whereDoesntHave('komponen_evaluasi')
                        ->whereDoesntHave('nilai_komponen')
                        ->whereDoesntHave('nilai_perkuliahan');
                })
                ->get();

        foreach ($prodi as $p) {
            $proses = $this->proses_nilai($p->id_prodi, $semester_aktif['id_semester'], $semester_aktif['nama_semester']);

            $this->info('Prodi: '.$p->nama_jenjang_pendidikan.' '.$p->nama_program_studi);
            $this->info('Komponen Evaluasi Diproses: '.$proses['komponen_evaluasi']);
            $this->info('Nilai Komponen Diproses: '.$proses['nilai_komponen']);
            $this->info('Nilai Perkuliahan Diproses: '.$proses['nilai_perkuliahan']);

            // return;
        }

    }

    private function proses_nilai($prodi, $semester, $nama_semester)
    {
        $kelas_kuliah = KelasKuliah::with('peserta_kelas_approved')
                    ->whereHas('peserta_kelas_approved')
                    ->whereDoesntHave('komponen_evaluasi')
                    ->whereDoesntHave('nilai_komponen')
                    ->whereDoesntHave('nilai_perkuliahan')
                    ->where('id_prodi', $prodi)
                    ->where('id_semester', $semester)
                    ->get();

        // Set Nilai default untuk mahasiswa
        $nilaiAngka = 86;
        $nilaiIndeks = 4.00;
        $nilaiHuruf = 'A';

        $komponen_evaluasi_proses = 0;
        $nilai_komponen_proses = 0;
        $nilai_perkuliahan_proses = 0;

        foreach ($kelas_kuliah as $k) {

            //Generate id aktivitas mengajar
            $id_komp_eval1 = Uuid::uuid4()->toString();
            $id_komp_eval2 = Uuid::uuid4()->toString();
            $id_komp_eval3 = Uuid::uuid4()->toString();
            $id_komp_eval4 = Uuid::uuid4()->toString();
            $id_komp_eval5 = Uuid::uuid4()->toString();
            $id_komp_eval6 = Uuid::uuid4()->toString();

            //Penyesuaian format bobot komponen evaluasi
            $bobot_participatory = 10/100;
            $bobot_project = 20/100;
            $bobot_assignment = 15/100;
            $bobot_quiz = 15/100;
            $bobot_midterm = 20/100;
            $bobot_finalterm = 20/100;

            DB::beginTransaction();

            // Getting Komponen Evaluasi Kelas
            $komponen_evaluasi = [];

            //Store data participatory
            $komponen_evaluasi[] = KomponenEvaluasiKelas::create(['feeder'=> 0, 'id_komponen_evaluasi'=> $id_komp_eval1, 'id_kelas_kuliah'=> $k->id_kelas_kuliah, 'id_jenis_evaluasi'=> 2,  'nama'=> '-', 'nama_inggris'=> 'Participatory Activity', 'nomor_urut'=> 1, 'bobot_evaluasi'=> $bobot_participatory]);

            //Store data project
            $komponen_evaluasi[] = KomponenEvaluasiKelas::create(['feeder'=> 0, 'id_komponen_evaluasi'=> $id_komp_eval2, 'id_kelas_kuliah'=> $k->id_kelas_kuliah, 'id_jenis_evaluasi'=> 3,  'nama'=> '-', 'nama_inggris'=> 'Project Outcomes', 'nomor_urut'=> 2, 'bobot_evaluasi'=> $bobot_project]);

            //Store data assignment
            $komponen_evaluasi[] = KomponenEvaluasiKelas::create(['feeder'=> 0, 'id_komponen_evaluasi'=> $id_komp_eval3, 'id_kelas_kuliah'=> $k->id_kelas_kuliah, 'id_jenis_evaluasi'=> 4,  'nama'=> 'TGS', 'nama_inggris'=> 'Assignment', 'nomor_urut'=> 3, 'bobot_evaluasi'=> $bobot_assignment]);

            //Store data quiz
            $komponen_evaluasi[] = KomponenEvaluasiKelas::create(['feeder'=> 0, 'id_komponen_evaluasi'=> $id_komp_eval4, 'id_kelas_kuliah'=> $k->id_kelas_kuliah, 'id_jenis_evaluasi'=> 4,  'nama'=> 'QIZ', 'nama_inggris'=> 'Quiz', 'nomor_urut'=> 4, 'bobot_evaluasi'=> $bobot_quiz]);

            //Store data midterm
            $komponen_evaluasi[] = KomponenEvaluasiKelas::create(['feeder'=> 0, 'id_komponen_evaluasi'=> $id_komp_eval5, 'id_kelas_kuliah'=> $k->id_kelas_kuliah, 'id_jenis_evaluasi'=> 4,  'nama'=> 'UTS', 'nama_inggris'=> 'Midterm Exam', 'nomor_urut'=> 5, 'bobot_evaluasi'=> $bobot_midterm]);

            //Store data finalterm
            $komponen_evaluasi[] = KomponenEvaluasiKelas::create(['feeder'=> 0, 'id_komponen_evaluasi'=> $id_komp_eval6, 'id_kelas_kuliah'=> $k->id_kelas_kuliah, 'id_jenis_evaluasi'=> 4,  'nama'=> 'UAS', 'nama_inggris'=> 'Finalterm Exam', 'nomor_urut'=> 6, 'bobot_evaluasi'=> $bobot_finalterm]);


            //Getting Mahasiswa Kelas Kuliah
            $mahasiswa_kelas = $k->peserta_kelas_approved;

            foreach ($mahasiswa_kelas as $mk) {

                foreach ($komponen_evaluasi as $komponen) {

                    NilaiKomponenEvaluasi::create([
                        'feeder' => 0,
                        'id_komponen_evaluasi' => $komponen->id_komponen_evaluasi, 
                        'id_kelas' => $k->id_kelas_kuliah, 
                        'id_registrasi_mahasiswa' => $mk->id_registrasi_mahasiswa, 
                        'urutan' => $komponen->nomor_urut,
                        'id_prodi' => $mk->id_prodi,
                        'nama_program_studi' => $mk->nama_program_studi,
                        'id_periode' => $k->semester_kelas,
                        'id_matkul' => $k->matkul,
                        'nama_mata_kuliah' => $mk->nama_mata_kuliah,
                        'nama_kelas_kuliah' => $k->nama_kelas_kuliah,
                        'sks_mata_kuliah' => $k->sks_matkul,
                        'nama_mahasiswa' => $mk->nama_mahasiswa,
                        'nim' => $mk->nim,
                        'id_jns_eval' => $komponen->id_jenis_evaluasi,
                        'nama' => $komponen->nama,
                        'nama_inggris' => $komponen->nama_inggris,
                        'bobot' => $komponen->bobot_evaluasi,
                        'angkatan' => $mk->angkatan,
                        'status_sync' => 'belum sync',
                        'nilai_komp_eval' => $nilaiAngka,
                    ]);

                    $nilai_komponen_proses++;

                }

                NilaiPerkuliahan::create([
                    'feeder' => 0,
                    'id_prodi' => $mk->id_prodi,
                    'nama_program_studi' => $mk->nama_program_studi,
                    'id_semester' => $semester,
                    'nama_semester' => $nama_semester,
                    'id_matkul' => $k->matkul,
                    'kode_mata_kuliah' => $k->kode_matkul,
                    'nama_mata_kuliah' => $k->nama_matkul,
                    'sks_mata_kuliah' => $k->sks_matkul,
                    'id_kelas_kuliah' => $k->id_kelas_kuliah,
                    'nama_kelas_kuliah' => $k->nama_kelas_kuliah,
                    'id_registrasi_mahasiswa' => $mk->id_registrasi_mahasiswa,
                    'id_mahasiswa' => $mk->id_mahasiswa,
                    'nim' => $mk->nim,
                    'nama_mahasiswa' => $mk->nama_mahasiswa,
                    'jurusan' => $mk->nama_program_studi,
                    'angkatan' => $mk->angkatan,
                    'nilai_angka' => $nilaiAngka,
                    'nilai_indeks' => $nilaiIndeks,
                    'nilai_huruf' => $nilaiHuruf,
                ]);

                $nilai_perkuliahan_proses++;

            }

            DB::commit();

            $komponen_evaluasi_proses++;
        }

        return [
            'komponen_evaluasi' => $komponen_evaluasi_proses,
            'nilai_komponen' => $nilai_komponen_proses,
            'nilai_perkuliahan' => $nilai_perkuliahan_proses
        ];
    }

}
